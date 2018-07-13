<?php 
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Developer_model extends CI_Model {
    public function admin_result($offset='', $per_page=''){     
        return $this->product_result($offset, $per_page);       
    }   
    public function getSubadmin($offSet = 0, $perPage = 0) {
        $UserName   = trim($this->input->get('title', TRUE));
        $email      = trim($this->input->get('email', TRUE));
        $userId     = trim($this->input->get('id', TRUE));      
        $order      = trim($this->input->get('order', TRUE));
        $this->db->select('admin_users.*');
        $this->db->from('admin_users');
         $this->db->where("admin_users.user_role", 3);
        if (isset($UserName) && !empty($UserName)) {
            $this->db->like('admin_users.first_name', $UserName);           
        }
        if (isset($email) && !empty($email)) {
           $this->db->like('admin_users.email', $email);         
        }         
        if ($userId) {
            $this->db->where("admin_users.id", $userId);
        }
        if ($this->input->get('start')) {
            $this->db->where("admin_users.created_date >", $this->input->get('start'));
        }
        if ($this->input->get('end')) {
            $this->db->where("admin_users.created_date <", $this->input->get('end'));
        }
        if (!empty($order)) {
            if ($order == 'NameAtoZ') {
                $this->db->order_by('admin_users.first_name', 'ASC');
            } else if ($order == 'NameZtoA') {
                $this->db->order_by('users.first_name', 'DESC');
            } else if ($order == 'ASC') {
                $this->db->order_by('admin_users.id', 'ASC');
            } else {
                $this->db->order_by('admin_users.id', 'DESC');
            }
        } else {
            $this->db->order_by('admin_users.id', 'DESC');
        }      
        if ($offSet >= 0 && $perPage > 0) {
            $this->db->limit($perPage, $offSet);
            $getdata = $this->db->get();
            $num = $getdata->num_rows();
            if ($num) {
                $data = $getdata->result();
                return $data;
            } else {
                return false;
            }
        } else {
            $getdata = $this->db->get();
            return $getdata->num_rows();
        }
    }
    public function product_result($offset='', $per_page=''){       
        $this->db->from('users');
        if($offset>=0 && $per_page>0){
            $this->db->limit($per_page,$offset);
            $query = $this->db->get();              
            if($query->num_rows()>0){
                $rows = $query->result_array();
                $table_heads = $this->common_model->get_result('ad_module_table_head', array('module_id'=>$this->input->get('module_id')), array('title','db_meta_key','id'), array('id','asc'));
                $data_a = array();
                $i = 0;
                if(!empty($rows)){
                    foreach ($rows as $row) {
                        if(!empty($table_heads)){ 
                            foreach ($table_heads as $table_head) {
                                if(!empty($table_head->title)&&$table_head->title=='Action'){
                                    $data_a[$i][$table_head->id] = $table_head->db_meta_key;
                                }else if(!empty($table_head->db_meta_key)){
                                    $data_a[$i][$table_head->id] = $row[$table_head->db_meta_key];
                                }            
                            }
                        }
                        $i++;
                    }                   
                }
                return $data_a;
            }else{
                return FALSE;
            }
        }else{
            return $this->db->count_all_results();
        }
    }    
    public function site_data(){        
        $array           = array();
        $array['title']  = 'User List';
        $array['btitle'] = 'User List';
        return $array;
    } 
    public function getCustomer($offSet=0, $perPage=0){
        $UserName    = trim($this->input->get('user_name',TRUE));
        $name        = trim($this->input->get('name', TRUE));
        $email       = trim($this->input->get('email',TRUE));
        $order       = trim($this->input->get('order',TRUE));
        $this->db->select('users.*, height.height_title, (SELECT COUNT(*) FROM '.TABLE_PRE.'follow_request WHERE '.TABLE_PRE.'follow_request.sender_id = '.TABLE_PRE.'users.id) as followingCount, (SELECT COUNT(*) FROM '.TABLE_PRE.'follow_request WHERE '.TABLE_PRE.'follow_request.receiver_id = '.TABLE_PRE.'users.id) as followersCount');
        $this->db->from('users');   
        $this->db->join('height', 'users.height = height.id', 'left');  
        if(isset($UserName) && !empty($UserName)){
            $this->db->where('users.user_name', $UserName);
        }
        if(isset($email) && !empty($email)){
           $this->db->like('users.email', $email);
        }
        if(!empty($name)){  
            $this->db->like('users.first_name', $name);
            $this->db->or_like('users.last_name', $name);
        }   
        if($this->input->get('start')){     
            $this->db->where("users.created_date >", $this->input->get('start'));   
        }
        if($this->input->get('end')){       
            $this->db->where("users.created_date <", $this->input->get('end')); 
        }
        if(!empty($order)){ 
            if($order == 'NameAtoZ'){ 
                $this->db->order_by('users.first_name', 'ASC');
            }else if($order == 'NameZtoA'){ 
                $this->db->order_by('users.first_name', 'DESC');
            }else if($order == 'ASC'){ 
                $this->db->order_by('users.id', 'ASC');
            }else{
                $this->db->order_by('users.id', 'DESC');
            }  
         }else {
            $this->db->order_by('users.id', 'DESC');
        }       
        if($offSet>=0 && $perPage>0){       
            $this->db->limit($perPage,$offSet);
            $getdata = $this->db->get(); 
            //echo $this->db->last_query(); exit();
            $num  = $getdata->num_rows();
            if($num){
               $data = $getdata->result();
               return $data;    
            }else{
                return false;
            }       
        }else{
            $getdata = $this->db->get();
            return $getdata->num_rows();
        }   

    }
    public function getInfluencers($offSet=0, $perPage=0){
        $UserName    = trim($this->input->get('user_name',TRUE));
        $userId      = trim($this->input->get('user_id',TRUE));
        $email       = trim($this->input->get('email',TRUE));
        $order       = trim($this->input->get('order',TRUE));
        $this->db->select('users.*, height.height_title, (SELECT COUNT(*) FROM '.TABLE_PRE.'follow_request WHERE '.TABLE_PRE.'follow_request.sender_id = '.TABLE_PRE.'users.id) as followingCount, (SELECT COUNT(*) FROM '.TABLE_PRE.'follow_request WHERE '.TABLE_PRE.'follow_request.receiver_id = '.TABLE_PRE.'users.id) as followersCount');
        $this->db->from('users');   
        $this->db->join('height', 'users.height = height.id', 'left');  
        if(isset($UserName) && !empty($UserName)){
            $this->db->like('users.user_name', $UserName);
            $this->db->or_like('users.first_name', $UserName);
            $this->db->or_like('users.last_name', $UserName);
        } 
        if($this->input->get('minimum_follow',TRUE)){
            $this->db->having('followersCount >=', $this->input->get('minimum_follow',TRUE));
        }else{
            $this->db->having('followersCount >', 0);
        }
        if(isset($email) && !empty($email)){
            $this->db->like('users.email', $email);
        }
        if($userId){
            $this->db->where("users.id", $userId);  
        }   
        if($this->input->get('start')){     
            $this->db->where("users.created_date >", $this->input->get('start'));   
        }
        if($this->input->get('end')){       
            $this->db->where("users.created_date <", $this->input->get('end')); 
        }
        if(!empty($order)){ 
            if($order == 'NameAtoZ'){ 
                $this->db->order_by('users.first_name', 'ASC');
            }else if($order == 'NameZtoA'){ 
                $this->db->order_by('users.first_name', 'DESC');
            }else if($order == 'ASC'){ 
                $this->db->order_by('users.id', 'ASC');
            }else{
                $this->db->order_by('users.id', 'DESC');
            }  
         }else {
            $this->db->order_by('followersCount', 'DESC');
        }       
        if($offSet>=0 && $perPage>0){       
            $this->db->limit($perPage,$offSet);
            $getdata = $this->db->get(); 
            //echo $this->db->last_query(); exit();
            $num  = $getdata->num_rows();
            if($num){
               $data = $getdata->result();
               return $data;    
            }else{
                return false;
            }       
        }else{
            $getdata = $this->db->get();
            return $getdata->num_rows();
        }   

    }
    public function getPost($offSet = 0, $perPage = 0) {
        $type       = trim($this->input->get('type', TRUE));      
        $post_id    = trim($this->input->get('id', TRUE)); 
        $userID     = trim($this->input->get('userID', TRUE)); 
        $post_title = trim($this->input->get('post_title', TRUE));
        $userName   = trim($this->input->get('userName', TRUE));
        $order      = trim($this->input->get('order', TRUE));
        $this->db->select('recipe_blog_image.*, users.first_name, users.last_name, (SELECT COUNT(*) FROM '.TABLE_PRE.'post_views WHERE `recipe_blog_image_id`='.TABLE_PRE.'recipe_blog_image.id) as views, (SELECT COUNT(*) FROM '.TABLE_PRE.'likes WHERE `recipe_blog_image_id`='.TABLE_PRE.'recipe_blog_image.id) as likes, (SELECT COUNT(*) FROM '.TABLE_PRE.'bookmark WHERE recipe_blog_image_id = '.TABLE_PRE.'recipe_blog_image.id) as bookmarked, (SELECT COUNT(*) FROM '.TABLE_PRE.'comments WHERE `recipe_blog_image_id`='.TABLE_PRE.'recipe_blog_image.id) as comments');
        $this->db->from('recipe_blog_image');
        $this->db->join('users', 'recipe_blog_image.user_id=users.id', 'left');
        $this->db->where("recipe_blog_image.status !=", '3');
        if (!empty($type)&&$type=='image') {
            $this->db->where("recipe_blog_image.type", 'image');
        }else{
            $this->db->where("recipe_blog_image.type", $type);
        }
        if (!empty($post_id)) {
            $this->db->where("recipe_blog_image.id", $post_id);
        }
        if (isset($post_title) && !empty($post_title)) {
            $this->db->like('recipe_blog_image.title', $post_title);   
        } 
        if(isset($userID) && !empty($userID)) {
            $this->db->where('recipe_blog_image.user_id', $userID);  
        }
        if(isset($userName) && !empty($userName)) {
            $this->db->like('users.first_name', $userName);  
        }  
        if ($this->input->get('start')) {
            $this->db->where("recipe_blog_image.created_date >", $this->input->get('start'));
        }
        if ($this->input->get('end')) {
            $this->db->where("recipe_blog_image.created_date <", $this->input->get('end'));
        }
        if (!empty($order)) {
            if ($order == 'NameAtoZ') {
                $this->db->order_by('recipe_blog_image.title', 'ASC');
            } else if ($order == 'NameZtoA') {
                $this->db->order_by('recipe_blog_image.title', 'DESC');
            } else if ($order == 'ASC') {
                $this->db->order_by('recipe_blog_image.id', 'ASC');
            } else {
                $this->db->order_by('recipe_blog_image.id', 'DESC');
            }
        } else {
            $this->db->order_by('recipe_blog_image.id', 'DESC');
        }      
        if ($offSet >= 0 && $perPage > 0) {
            $this->db->limit($perPage, $offSet);
            $getdata = $this->db->get();
            $num = $getdata->num_rows();
            if ($num) {
                $data = $getdata->result();
                return $data;
            } else {
                return false;
            }
        } else {
            $getdata = $this->db->get();
            return $getdata->num_rows();
        }
    }
    public function getPostDetails() {
        $id       = trim($this->input->get('post_id', TRUE)); 
        $this->db->select('recipe_blog_image.*, users.first_name, users.last_name, (SELECT COUNT(*) FROM '.TABLE_PRE.'likes WHERE `recipe_blog_image_id`='.TABLE_PRE.'recipe_blog_image.id) as likes, (SELECT COUNT(*) FROM '.TABLE_PRE.'bookmark WHERE recipe_blog_image_id = '.TABLE_PRE.'recipe_blog_image.id) as bookmarked, (SELECT COUNT(*) FROM '.TABLE_PRE.'comments WHERE `recipe_blog_image_id`='.TABLE_PRE.'recipe_blog_image.id) as comments');
        $this->db->from('recipe_blog_image');
        $this->db->join('users', 'recipe_blog_image.user_id=users.id', 'left');
        $this->db->where("recipe_blog_image.status !=", '3');
        if(isset($id) && !empty($id)) {
            $this->db->like('recipe_blog_image.id', $id);   
        }
        $getdata = $this->db->get();
        $num = $getdata->num_rows();
        if ($num) {
            $data = $getdata->row();
            return $data;
        } else {
            return false;
        }
    }
    public function postActivilty() {
        $type       = trim($this->input->get('types', TRUE)); 
        $post_id    = trim($this->input->get('post_id', TRUE)); 
        if($type=='likes'){
            $this->db->select('likes.*, users.first_name, users.last_name, users.email, users.profile_pic');
            $this->db->from('likes');
            $this->db->join('users', 'likes.user_id=users.id', 'left');
            $this->db->where('likes.recipe_blog_image_id', $post_id);
            $this->db->order_by('likes.id', 'DESC');
        } else if($type=='bookmarked'){
            $this->db->select('bookmark.*, users.first_name, users.last_name, users.email, users.profile_pic');
            $this->db->from('bookmark');
            $this->db->join('users', 'bookmark.user_id=users.id', 'left');
            $this->db->where('bookmark.recipe_blog_image_id', $post_id);
            $this->db->order_by('bookmark.id', 'DESC');
        } else if($type=='comments'){
            $this->db->select('comments.*, users.first_name, users.last_name, users.email, users.profile_pic');
            $this->db->from('comments');
            $this->db->join('users', 'comments.user_id=users.id', 'left');
            $this->db->where('comments.recipe_blog_image_id', $post_id);
            $this->db->order_by('comments.id', 'DESC');
        }  else if($type=='views'){
            $this->db->select('post_views.*, users.first_name, users.last_name, users.email, users.profile_pic');
            $this->db->from('post_views');
            $this->db->join('users', 'post_views.user_id=users.id', 'left');
            $this->db->where('post_views.recipe_blog_image_id', $post_id);
            $this->db->order_by('post_views.id', 'DESC');
        }  
        $getdata = $this->db->get();
        //echo $this->db->last_query();
        $num = $getdata->num_rows();
        if ($num) {
            $data = $getdata->result();
            return $data;
        } else {
            return false;
        }
    }
    public function getUserDetails($user_id=''){        
        $this->db->select('users.*, plans.plan_title');
        $this->db->from('users');   
        $this->db->join('plans', 'users.plan = plans.id','left');               
        if(!empty($user_id)){
            $this->db->where("users.id", $user_id); 
        }else{
            $this->db->where("users.id", user_id());    
        }           
        $getdata = $this->db->get();            
        $num  = $getdata->num_rows();   
        if($num){
           $data = $getdata->row();
           return $data;        
        }else{
            return false;
        }
    }   
    public function getUserPlans($user_id=''){        
        $this->db->select('userPlans.*, goal_setter.height, goal_setter.wieght, goal_setter.loseDay, goal_setter.loseWeight');
        $this->db->from('userPlans');       
        $this->db->join('goal_setter', 'userPlans.goal_id= goal_setter.id','left');     
        if(!empty($user_id)){
            $this->db->where("userPlans.user_id", $user_id);    
        }else{
            $this->db->where("userPlans.user_id", user_id());   
        }       
        $this->db->where("userPlans.status !=", '3');   
        $getdata = $this->db->get();            
        $num  = $getdata->num_rows();   
        if($num){
           $data = $getdata->result();
           return $data;        
        }else{
            return false;
        }
    }
    public function getUserIdFShowPost($user_id=''){
        $this->db->select('receiver_id');
        $this->db->from('follow_request');               
        $this->db->where("sender_id", $user_id);    
        $this->db->where("accepted_status", '1');   
        $getdata = $this->db->get();            
        $num     = $getdata->num_rows();  
        $userids = array(); 
        if($num){
           return $getdata->result();;
        }else{
            return false;
        }
    }
    public function getUserIdShowPost(){        
        $this->db->select('id');
        $this->db->from('users');               
        $this->db->where("users.privacy", '1');    
        $this->db->where("users.status", '1');   
        $getdata = $this->db->get();  
        //echo $this->db->last_query(); exit();          
        $num        = $getdata->num_rows();  
        $userids    = array();         
        if($num){
           $data = $getdata->result();
           if(!empty($data)){
                foreach($data as $dataR){
                    $userids[] = $dataR->id;
                }                
           }                 
        }
        if(user_logged_in()){ 
            $userids[]  = user_id();
            $fUsers = $this->getUserIdFShowPost(user_id());
            if(!empty($fUsers)){
                foreach($fUsers as $fUser){
                    $userids[] = $fUser->receiver_id;
                }
            }
        }        
        array_unique($userids);    
        if(!empty($userids)){
            return $userids;
        }else{
            return false;
        }
    }
    public function getUserIdFrShowPost($user_id=''){
        if(!empty($user_id)){ 
            $userids[]  = $user_id;
            $fUsers     = $this->getUserIdFShowPost($user_id);
            if(!empty($fUsers)){
                foreach($fUsers as $fUser){
                    $userids[] = $fUser->receiver_id;
                }
            }
        }        
        array_unique($userids);    
        if(!empty($userids)){
            return $userids;
        }else{
            return false;
        }
    }
    
    public function get_recipe_blog_images($userId='', $offSet=0, $perPage=0, $counter='',$singleUser=''){
        $hash_datas = $this->get_recipe_blog_images_hash(NULL,0,100000);
        $hashKeys   = array(); 
        if(!empty($hash_datas)){
            foreach($hash_datas as $hash_data){
                $hashKeys[] = $hash_data->id; 
            }
        }
        if(!empty($userId)){
            $allowPostUsers = $this->getUserIdFrShowPost($userId);
        }else{
            $allowPostUsers = $this->getUserIdShowPost();
        }
        $this->db->select('recipe_blog_image.*, (SELECT COUNT(*) FROM '.TABLE_PRE.'images WHERE meta_id = '.TABLE_PRE.'recipe_blog_image.id) as imgCounts');
        $this->db->from('recipe_blog_image');     
        $this->db->join('comments', 'comments.recipe_blog_image_id = recipe_blog_image.id', 'left');     
        $this->db->group_by('recipe_blog_image.id');   
        $this->db->order_by('recipe_blog_image.id', 'DESC');   
        if(!empty($userId)&&!empty($singleUser)){   
            $this->db->where("recipe_blog_image.user_id", $userId);     
        }
        if($this->input->get('title')){ 
            $hashSt   = substr($this->input->get('title'),0, 1);
            if($hashSt=='#'){
                $hashKey  = $this->input->get('title');
            }else{
                $hashKey  = '#'.$this->input->get('title');
            }
            $whereH = "(";
            $whereH .= "recipe_blog_image.title LIKE '%".$this->input->get('title')."%' ";
            $whereH .= " OR recipe_blog_image.caption LIKE '%".$this->input->get('title')."%'";
            $whereH .= " OR recipe_blog_image.description LIKE '%".$this->input->get('title')."%'";
            $whereH .= " OR recipe_blog_image.ingredients LIKE '%".$this->input->get('title')."%'";
            $whereH .= " OR recipe_blog_image.content LIKE '%".$this->input->get('title')."%'";
            $whereH .= " OR recipe_blog_image.instructions LIKE '%".$this->input->get('title')."%'";
            $whereH .= " OR comments.comments LIKE '%".$this->input->get('title')."%'";
            $whereH .= ")";
           /* $whereH .= " AND ";
            $whereH .= "(";
            $whereH .= "recipe_blog_image.title NOT LIKE '%".$hashKey."%' ";
            $whereH .= " AND recipe_blog_image.caption NOT LIKE '%".$hashKey."%'";
            $whereH .= " AND recipe_blog_image.description NOT LIKE '%".$hashKey."%'";
            $whereH .= " AND recipe_blog_image.ingredients NOT LIKE '%".$hashKey."%'";
            $whereH .= " AND recipe_blog_image.content NOT LIKE '%".$hashKey."%'";
            $whereH .= " AND recipe_blog_image.instructions NOT LIKE '%".$hashKey."%'";
            $whereH .= " AND comments.comments NOT LIKE '%".$hashKey."%'";
            $whereH .= ")";*/
            $whereH .= " AND (";
            $whereH .= "rok_recipe_blog_image.id NOT IN ( SELECT CASE WHEN recipe_blog_image_id is NULL THEN 0 ELSE recipe_blog_image_id END AS post_id FROM   rok_comments 
                            WHERE  rok_comments.`comments` LIKE '%".$hashKey."%')";
            $whereH .= ")  ";
            $this->db->where($whereH); 
            if(!empty($hashKeys)){
                $whereIn = "(recipe_blog_image.id NOT IN(".implode(',', $hashKeys).") )"; 
                $this->db->where($whereIn); 
            } 
        }   
        if($this->input->get('type')){  
            $this->db->where("recipe_blog_image.type", $this->input->get('type'));  
        }        
        if(!empty($allowPostUsers)){            
            $whereIn = "(recipe_blog_image.user_id IN(".implode(',', $allowPostUsers).") )"; 
            $this->db->where($whereIn);    
        }
        $this->db->where("recipe_blog_image.status", 1);    
        if ($offSet >= 0 && $perPage > 0) {
            $this->db->limit($perPage, $offSet);
        }   
        $getdata = $this->db->get();
        //echo $this->db->last_query(); // exit();
        //echo '<br/><br/><br/>';
         // exit();
        $num     = $getdata->num_rows();    
        if($num){
            if(!empty($counter)){
                $data = $getdata->num_rows();
            }else{
                $data = $getdata->result();
            }
           return $data;        
        }else{
            return false;
        }   
    }
    public function get_recipe_blog_images_hash($userId='', $offSet=0, $perPage=0, $counter=''){
        $this->db->select('recipe_blog_image.*, (SELECT COUNT(*) FROM '.TABLE_PRE.'images WHERE meta_id = '.TABLE_PRE.'recipe_blog_image.id) as imgCounts');
        $this->db->from('recipe_blog_image');  
        $this->db->join('comments', 'comments.recipe_blog_image_id = recipe_blog_image.id', 'left');     
        $this->db->group_by('recipe_blog_image.id');     
        //$this->db->join('comments', '');     
        $this->db->order_by('recipe_blog_image.id', 'DESC');   
        if(!empty($userId)){    
            $this->db->where("recipe_blog_image.user_id", $userId);     
        }
        if($this->input->get('title')){ 
            $hashSt = substr($this->input->get('title'),0, 1);
            if($hashSt=='#'){
                $this->db->like("recipe_blog_image.title", $this->input->get('title'));     
                $this->db->or_like("recipe_blog_image.caption", $this->input->get('title'));        
                $this->db->or_like("recipe_blog_image.content", $this->input->get('title'));
                $this->db->or_like("recipe_blog_image.description", $this->input->get('title'));
                $this->db->or_like("recipe_blog_image.ingredients", $this->input->get('title'));
                $this->db->or_like("recipe_blog_image.instructions", $this->input->get('title'));
                $this->db->or_like("recipe_blog_image.location", $this->input->get('title'));
                $this->db->or_like("comments.comments", $this->input->get('title')); 
            }else{                
                $this->db->like("recipe_blog_image.title", '#'.$this->input->get('title'));     
                $this->db->or_like("recipe_blog_image.caption", '#'.$this->input->get('title'));        
                $this->db->or_like("recipe_blog_image.description", '#'.$this->input->get('title'));        
                $this->db->or_like("recipe_blog_image.content", '#'.$this->input->get('title'));
                $this->db->or_like("recipe_blog_image.ingredients", '#'.$this->input->get('title'));
                $this->db->or_like("recipe_blog_image.instructions", '#'.$this->input->get('title'));
                $this->db->or_like("recipe_blog_image.location", '#'.$this->input->get('title'));
                $this->db->or_like("comments.comments", '#'.$this->input->get('title'));        
            }
        }   
        if($this->input->get('type')){  
            $this->db->where("recipe_blog_image.type", $this->input->get('type'));  
        }
        $this->db->where("recipe_blog_image.status", 1);    
        if ($offSet >= 0 && $perPage > 0) {
            $this->db->limit($perPage, $offSet);
        }   
        $getdata = $this->db->get();
        //echo $this->db->last_query(); //exit();
        $num     = $getdata->num_rows();    
        if($num){
            if(!empty($counter)){
                $data = $getdata->num_rows();
            }else{
                $data = $getdata->result();
            }
           return $data;        
        }else{
            return false;
        }   
    }   
    public function get_users(){
        $this->db->select('users.*');
        $this->db->from('users');     
        $this->db->order_by('users.first_name', 'ASC');           
        if($this->input->get('title')){ 
            $whstr ="(first_name LIKE '%".$this->input->get('title')."%' OR last_name LIKE '%".$this->input->get('title')."%' OR user_name LIKE '%".$this->input->get('title')."%')";      
            $this->db->where($whstr); 
        }
        $this->db->where("users.status", 1); 
        $this->db->where("users.id !=", user_id()); 
        $getdata = $this->db->get();
        //echo $this->db->last_query();
        $num     = $getdata->num_rows();    
        if($num){
            return $getdata->result();      
        }else{
            return false;
        }   
    }
    public function getLikesBookMarkBlogImges($userId='',$offSet=0, $perPage=0){
        if($this->input->get('type')=='likes'){
            $this->db->select('recipe_blog_image.*');
            $this->db->from('likes');     
            $this->db->join('recipe_blog_image', 'likes.recipe_blog_image_id=recipe_blog_image.id', 'left');     
            $this->db->where("recipe_blog_image.status", 1); 
            if(!empty($userId)){    
                $this->db->where("likes.user_id", $userId);     
            }
            $this->db->order_by('likes.id', 'DESC');   
        }else{
            $this->db->select('recipe_blog_image.*');
            $this->db->from('bookmark');     
            $this->db->join('recipe_blog_image', 'bookmark.recipe_blog_image_id=recipe_blog_image.id', 'left');     
            if(!empty($userId)){    
                $this->db->where("bookmark.user_id", $userId);      
            }
            $this->db->where("recipe_blog_image.status", 1); 
            $this->db->order_by('bookmark.id', 'DESC');   
        }        
        if ($offSet >= 0 && $perPage > 0) {
            $this->db->limit($perPage, $offSet);
        }   
        $getdata = $this->db->get();
        $num     = $getdata->num_rows();    
        if($num){
           $data = $getdata->result();
           return $data;        
        }else{
            return false;
        }   
    }
    public function get_post_details(){
        $this->db->select('recipe_blog_image.*, users.user_name, users.profile_pic ');
        $this->db->from('recipe_blog_image');     
        $this->db->join('users','recipe_blog_image.user_id=users.id', 'left');     
        $this->db->order_by('recipe_blog_image.id', 'DESC');   
        if($this->input->get('post_id')){   
            $this->db->where("recipe_blog_image.id", $this->input->get('post_id'));     
        }
        $this->db->where("recipe_blog_image.status", 1);
        $getdata = $this->db->get();
        //echo $this->db->last_query(); exit();
        $num     = $getdata->num_rows();    
        if($num){
           $data = $getdata->row();
           return $data;        
        }else{
            return false;
        }   
    }
    public function getPreviewNext($type=''){
        $this->db->select('recipe_blog_image.id');
        $this->db->from('recipe_blog_image');
        $this->db->where("recipe_blog_image.status", 1);      
        if($this->input->get('post_id')&&$type=='prev'){    
            $this->db->where("recipe_blog_image.id <", $this->input->get('post_id'));   
            $this->db->order_by('recipe_blog_image.id', 'DESC');    
        }
        if($this->input->get('post_id')&&$type=='next'){    
            $this->db->where("recipe_blog_image.id >", $this->input->get('post_id'));
            $this->db->order_by('recipe_blog_image.id', 'ASC');         
        }
        if($this->input->get('viewUser')){
            $this->db->where("recipe_blog_image.user_id", $this->input->get('viewUser'));
        }
        if($this->input->get('type')){  
            $this->db->where("recipe_blog_image.type", $this->input->get('type'));      
        }
        $this->db->limit(1); 
        $getdata = $this->db->get();    
        //echo $this->db->last_query(); exit(); 
        $num     = $getdata->num_rows();    
        if($num){
           $data = $getdata->row()->id;
           return $data;        
        }else{
            return false;
        }   
    }
    public function get_comments($post_id=''){
        $this->db->select('comments.*, users.user_name, users.profile_pic as profile_pic_file');
        $this->db->from('comments');     
        $this->db->join('users','comments.user_id=users.id', 'left');     
        $this->db->order_by('comments.id', 'DESC');   
        if(!empty($post_id)){   
            $this->db->where("comments.recipe_blog_image_id", $post_id);        
        }
        $getdata = $this->db->get();
        $num     = $getdata->num_rows();    
        if($num){
           $data = $getdata->result();
           return $data;        
        }else{
            return false;
        }   
    }
    public function getHeight($offSet=0, $perPage=0){
        $this->db->select('height.*');
        $this->db->from('height');    
        if ($this->input->get('id')) {
            $this->db->where("height.id", $this->input->get('id'));
        }
        if ($this->input->get('order')) {
            if ($this->input->get('order') == 'ASC') {
                $this->db->order_by('height.id', 'ASC');
            } else {
                $this->db->order_by('height.id', 'DESC');
            }
        } else {
            $this->db->order_by('height.id', 'DESC');
        } 
        $this->db->where("height.status !=", 3);    
        if($offSet>=0 && $perPage>0){           
            $this->db->limit($perPage,$offSet);
            $getdata = $this->db->get(); 
            $num  = $getdata->num_rows();       
            if($num){
               $data = $getdata->result();
               return $data;        
            }else{
                return false;
            }       
        }else{
            $getdata = $this->db->get();
            return $getdata->num_rows();    
        }       
    }
    public function getPlan($offSet=0, $perPage=0){
        $this->db->select('plans.*');
        $this->db->from('plans');    
        if ($this->input->get('subscription_id')) {
            $this->db->where("plans.id", $this->input->get('subscription_id'));
        }
        if ($this->input->get('subscription_title')) {
            $this->db->like("plans.plan_title", $this->input->get('subscription_title'));
        }
        if ($this->input->get('subscription_amount')) {
            $this->db->where("plans.amount", $this->input->get('subscription_amount'));
        }
        if ($this->input->get('order')) {
            if ($this->input->get('order') == 'ASC') {
                $this->db->order_by('plans.id', 'ASC');
            } else {
                $this->db->order_by('plans.id', 'DESC');
            }
        } else {
            $this->db->order_by('plans.id', 'DESC');
        } 
        $this->db->where("plans.status !=", 3); 
        if($offSet>=0 && $perPage>0){           
            $this->db->limit($perPage,$offSet);
            $getdata = $this->db->get(); 
            $num  = $getdata->num_rows();       
            if($num){
               $data = $getdata->result();
               return $data;        
            }else{
                return false;
            }       
        }else{
            $getdata = $this->db->get();
            return $getdata->num_rows();    
        }       
    }
    public function getServicePlan($offSet=0, $perPage=0){
        $this->db->select('service_plan.*');
        $this->db->from('service_plan');    
        if ($this->input->get('plan_id')) {
            $this->db->where("service_plan.id", $this->input->get('plan_id'));
        }
        if ($this->input->get('plan_title')) {
            $this->db->like("service_plan.title", $this->input->get('plan_title'));
        }
        if ($this->input->get('order')) {
            if ($this->input->get('order') == 'ASC') {
                $this->db->order_by('service_plan.id', 'ASC');
            } else {
                $this->db->order_by('service_plan.id', 'DESC');
            }
        } else {
            $this->db->order_by('service_plan.id', 'DESC');
        } 
        $this->db->where("service_plan.status !=", 3);  
        if($offSet>=0 && $perPage>0){           
            $this->db->limit($perPage,$offSet);
            $getdata = $this->db->get(); 
            $num  = $getdata->num_rows();       
            if($num){
               $data = $getdata->result();
               return $data;        
            }else{
                return false;
            }       
        }else{
            $getdata = $this->db->get();
            return $getdata->num_rows();    
        }       
    }
    public function getPlanCategory($offSet=0, $perPage=0){
        $this->db->select('service_plan_category.*, service_plan.title as plan_name');
        $this->db->from('service_plan_category');    
        $this->db->join('service_plan', 'service_plan_category.plan_id = service_plan.id');    
        if ($this->input->get('category_id')) {
            $this->db->where("service_plan_category.id", $this->input->get('category_id'));
        }
        if ($this->input->get('category_name')) {
            $this->db->where("service_plan_category.title", $this->input->get('category_name'));
        }
        if ($this->input->get('plan_id')&&$this->input->get('plan_id')!='all') {
            $this->db->where("service_plan.id", $this->input->get('plan_id'));
        }
        if ($this->input->get('order')) {
            if ($this->input->get('order') == 'ASC') {
                $this->db->order_by('service_plan_category.id', 'ASC');
            } else {
                $this->db->order_by('service_plan_category.id', 'DESC');
            }
        } else {
            $this->db->order_by('service_plan_category.id', 'DESC');
        } 
        $this->db->where("service_plan_category.status !=", 3); 
        if($offSet>=0 && $perPage>0){           
            $this->db->limit($perPage,$offSet);
            $getdata = $this->db->get(); 
            $num  = $getdata->num_rows();       
            if($num){
               $data = $getdata->result();
               return $data;        
            }else{
                return false;
            }       
        }else{
            $getdata = $this->db->get();
            return $getdata->num_rows();    
        }       
    }
    public function get_plan_type($offSet=0, $perPage=0){
        $this->db->select('service_plan_item.*, service_plan.title as plan_name, service_plan_category.title as category_name');
        $this->db->from('service_plan_item');  
        $this->db->join('service_plan_category', 'service_plan_item.category_id = service_plan_category.id'); 
        $this->db->join('service_plan', 'service_plan_category.plan_id = service_plan.id');   
        if ($this->input->get('type_id')) {
            $this->db->where("service_plan_item.id", $this->input->get('type_id'));
        }
        if ($this->input->get('plan_type')) {
            $this->db->like("service_plan_item.title", $this->input->get('plan_type'));
        }
        if ($this->input->get('plan_id')&&$this->input->get('plan_id')!='all') {
            $this->db->where("service_plan_category.plan_id", $this->input->get('plan_id'));
        }
        if ($this->input->get('category_id')&&$this->input->get('category_id')!='all') {
            $this->db->where("service_plan_item.category_id", $this->input->get('category_id'));
        }        
        if ($this->input->get('order')) {
            if ($this->input->get('order') == 'ASC') {
                $this->db->order_by('service_plan_item.id', 'ASC');
            } else {
                $this->db->order_by('service_plan_item.id', 'DESC');
            }
        } else {
            $this->db->order_by('service_plan_item.id', 'DESC');
        } 
        $this->db->where("service_plan_item.status !=", 3); 
        if($offSet>=0 && $perPage>0){           
            $this->db->limit($perPage,$offSet);
            $getdata = $this->db->get(); 
            $num  = $getdata->num_rows();       
            if($num){
               $data = $getdata->result();
               return $data;        
            }else{
                return false;
            }       
        }else{
            $getdata = $this->db->get();
            return $getdata->num_rows();    
        }       
    }
    public function get_service_plan_user_exercise($offSet=0, $perPage=0){
        $this->db->select('service_plan_user_exercise.*, service_plan_category.title as category_name, service_plan_item.title as sub_category_name');
        $this->db->from('service_plan_user_exercise');    
        $this->db->join('service_plan_category', 'service_plan_user_exercise.category_id = service_plan_category.id', 'left');    
        $this->db->join('service_plan_item', 'service_plan_user_exercise.sub_category_id = service_plan_item.id', 'left');    
        if ($this->input->get('plan_id')) {
            $this->db->where("service_plan_user_exercise.id", $this->input->get('plan_id'));
        }
        if ($this->input->get('plan_title')) {
            $this->db->like("service_plan_user_exercise.title", $this->input->get('plan_title'));
        }
        if ($this->input->get('category_id')) {
            $this->db->where("service_plan_user_exercise.category_id", $this->input->get('category_id'));
        }
        if ($this->input->get('sub_category_id')) {
            $this->db->where("service_plan_user_exercise.sub_category_id", $this->input->get('sub_category_id'));
        }
        if ($this->input->get('order')) {
            if ($this->input->get('order') == 'ASC') {
                $this->db->order_by('service_plan_user_exercise.id', 'ASC');
            } else {
                $this->db->order_by('service_plan_user_exercise.id', 'DESC');
            }
        } else {
            $this->db->order_by('service_plan_user_exercise.id', 'DESC');
        } 
        $this->db->where("service_plan_user_exercise.status !=", 3);    
        if($offSet>=0 && $perPage>0){           
            $this->db->limit($perPage,$offSet);
            $getdata = $this->db->get(); 
            $num  = $getdata->num_rows();       
            if($num){
               $data = $getdata->result();
               return $data;        
            }else{
                return false;
            }       
        }else{
            $getdata = $this->db->get();
            return $getdata->num_rows();    
        }       
    }
    public function get_service_plan_diet_items($offSet=0, $perPage=0){
        $this->db->select('service_plan_diet_items.*, service_plan_category.title as category_name, service_plan_item.title as sub_category_name');
        $this->db->join('service_plan_category', 'service_plan_diet_items.category_id = service_plan_category.id', 'left');    
        $this->db->join('service_plan_item', 'service_plan_diet_items.sub_category_id = service_plan_item.id', 'left');    
        $this->db->from('service_plan_diet_items');    
        if ($this->input->get('food_id')) {
            $this->db->where("service_plan_diet_items.id", $this->input->get('food_id'));
        }
        if ($this->input->get('food')) {
            $this->db->like("service_plan_diet_items.item_title", $this->input->get('food'));
        }
        if ($this->input->get('category_id')) {
            $this->db->where("service_plan_diet_items.category_id", $this->input->get('category_id'));
        }
        if ($this->input->get('sub_category_id')) {
            $this->db->where("service_plan_diet_items.sub_category_id", $this->input->get('sub_category_id'));
        }
        if ($this->input->get('order')) {
            if ($this->input->get('order') == 'ASC') {
                $this->db->order_by('service_plan_diet_items.id', 'ASC');
            } else {
                $this->db->order_by('service_plan_diet_items.id', 'DESC');
            }
        } else {
            $this->db->order_by('service_plan_diet_items.id', 'DESC');
        } 
        $this->db->where("service_plan_diet_items.status !=", 3);   
        if($offSet>=0 && $perPage>0){           
            $this->db->limit($perPage,$offSet);
            $getdata = $this->db->get(); 
            $num  = $getdata->num_rows();       
            if($num){
               $data = $getdata->result();
               return $data;        
            }else{
                return false;
            }       
        }else{
            $getdata = $this->db->get();
            return $getdata->num_rows();    
        }       
    }
    public function getUserSeting($user_id=''){        
        $this->db->select('users.privacy as profileVisibility, users.advancedMetricsTracking, users.useMetricsSystem, users.contactEmail, users.contactMobile, users.paypalEmail, plans.plan_title, plans.amount, plans.days, plans.message');
        $this->db->from('users');           
        $this->db->join('plans', 'users.plan = plans.id','left');           
        if(!empty($user_id)){
            $this->db->where("users.id", $user_id); 
        }else{
            $this->db->where("users.id", user_id());    
        }           
        $getdata = $this->db->get();            
        $num  = $getdata->num_rows();   
        if($num){
           $data = $getdata->row();
           return $data;        
        }else{
            return false;
        }
    }   
    public function getCategoryItem($categortID=''){        
        $this->db->select('itemPic as serviceItemPic,title as serviceItemTitle, id as serviceItemID');
        $this->db->from('service_plan_item');       
        $this->db->where("service_plan_item.status", 1);                
        $this->db->where("service_plan_item.category_id", $categortID);             
        $getdata = $this->db->get();            
        $num  = $getdata->num_rows();   
        if($num){
           $rows  = $getdata->result(); 
           $items = array();
           if(!empty($rows)){
                foreach($rows as $row){
                    $row->serviceItemPic =  (!empty($row->serviceItemPic)&&file_exists('assets/uploads/planItem/thumbnails/'.$row->serviceItemPic))?base_url().'assets/uploads/planItem/thumbnails/'.$row->serviceItemPic:'';
                    $items[] = $row;                    
                }           
           } 
           return $items;   
        }else{
            return false;
        }
    }
    public function getServicePlanItem($planID=''){        
        $this->db->select('title,id');
        $this->db->from('service_plan_category');       
        $this->db->where("service_plan_category.status", 1);
        if(!empty($planID)){
            $this->db->where("service_plan_category.plan_id", $planID);                         
        }
        $getdata = $this->db->get();            
        $num  = $getdata->num_rows();   
        $rows = array();
        if($num){
           $data = $getdata->result();
           if(!empty($data)){
                foreach($data as $dataRow){
                    $items  =   $this->getCategoryItem($dataRow->id);
                    $rows[] =   array(  'categoryID'    => $dataRow->id, 
                                        'categoryTitle' => $dataRow->title,
                                        'items'         => $items
                                    );
                }
           }        
        }
        return $rows;
    }
    public function getServicePlanExercise($planID=''){        
        $this->db->select('exercise_title, cacalories, exercise_details, exercise_pic, exercise_instruction,id as exerciseID');
        $this->db->from('service_plan_user_exercise');      
        $this->db->where("service_plan_user_exercise.status", 1);               
        $this->db->where("service_plan_user_exercise.plan_id", $planID);                
        $getdata = $this->db->get();            
        $num  = $getdata->num_rows();   
        if($num){         
           $rows  = $getdata->result(); 
           $items = array();
           if(!empty($rows)){
                foreach($rows as $row){
                    $row->exercise_pic =  (!empty($row->exercise_pic)&&file_exists('assets/uploads/plansExercise/thumbnails/'.$row->exercise_pic))?base_url().'assets/uploads/plansExercise/thumbnails/'.$row->exercise_pic:'';
                    $items[] = $row;                    
                }           
           } 
           return $items;               
        }else{
            return false;
        }
    }
    public function get_plan_list_type($itemID=''){        
        $this->db->select('service_plan_diet_items.*');
        $this->db->from('service_plan_diet_items');     
        $this->db->where("service_plan_diet_items.status", 1);     
        $whereIDS = "service_plan_diet_items.sub_category_id IN (".$itemID.")";         
        $this->db->where($whereIDS);                
        $getdata = $this->db->get();            
        $num  = $getdata->num_rows();   
        if($num){
           return $getdata->result();           
        }else{
            return false;
        }
    }
    public function get_plan_list_items($itemID=''){        
        $this->db->select('service_plan_diet_items.*');
        $this->db->from('service_plan_diet_items');     
        $this->db->where("service_plan_diet_items.status", 1);     
        $whereIDS = "service_plan_diet_items.id IN (".$itemID.")";      
        $this->db->where($whereIDS);                
        $getdata = $this->db->get();            
        $num  = $getdata->num_rows();   
        if($num){
           return $getdata->result();           
        }else{
            return false;
        }
    }
    public function getWorkOutPlanDayDetails($exID=''){
        $this->db->select('service_plan_works_new.*, service_plan_user_exercise.exercise_title as item_title, service_plan_user_exercise.cacalories, service_plan_user_exercise.exercise_pic, service_plan_user_exercise.measureUnit, service_plan_user_exercise.category_id');
        $this->db->from('service_plan_works_new');     
        $this->db->join('service_plan_user_exercise', 'service_plan_works_new.exercise_id=service_plan_user_exercise.id', 'left');
        $this->db->join('userPlans', 'service_plan_works_new.plan_id=userPlans.id', 'left');     
        if(!empty($exID)){
            $this->db->where("service_plan_works_new.id", $exID);
        }
        $this->db->order_by('service_plan_works_new.id', 'DESC');       
        $getdata = $this->db->get();
        $num     = $getdata->num_rows();    
        if($num){
           $data = $getdata->row();
           return $data;        
        }else{
            return false;
        }
    }
    public function getDietPlanDayDetails($foodID=''){
        $this->db->select('diet_plan_works_new.*, service_plan_diet_items.item_title, service_plan_diet_items.cacalories, service_plan_diet_items.protein, service_plan_diet_items.fat, service_plan_diet_items.carbohydrate, service_plan_diet_items.fiber, service_plan_diet_items.suger, service_plan_diet_items.exercise_pic');
        $this->db->from('diet_plan_works_new');     
        $this->db->join('service_plan_diet_items', 'diet_plan_works_new.item_id=service_plan_diet_items.id', 'left');
        $this->db->join('userPlans', 'diet_plan_works_new.plan_id=userPlans.id', 'left');     
        if(!empty($foodID)){
            $this->db->where("diet_plan_works_new.id", $foodID);
        }
        $this->db->order_by('diet_plan_works_new.id', 'DESC');      
        $getdata = $this->db->get();
        $num     = $getdata->num_rows();    
        if($num){
           $data = $getdata->row();
           return $data;        
        }else{
            return false;
        }
    }   
    public function getWorkOutPlanDayNew($date='', $currentPlan='', $user_id=''){
        $this->db->select('service_plan_works_new.*, service_plan_user_exercise.exercise_title as item_title, service_plan_user_exercise.cacalories, service_plan_user_exercise.exercise_pic, service_plan_user_exercise.measureUnit, userPlans.goal_id');
        $this->db->from('service_plan_works_new');     
        $this->db->join('service_plan_user_exercise', 'service_plan_works_new.exercise_id=service_plan_user_exercise.id', 'left');
        $this->db->join('userPlans', 'service_plan_works_new.plan_id = userPlans.id', 'left');     
        if(!empty($weekID)){
            $this->db->where("service_plan_works_new.week_id", $weekID);
        }   
        if($this->input->get('user_id')){           
            $this->db->where("userPlans.user_id", $this->input->get('user_id'));    
        }else if(!empty($user_id)){         
            $this->db->where("userPlans.user_id", $user_id);    
        }else{
            $this->db->where("userPlans.user_id", user_id());   
        }   
        if(!empty($currentPlan)){
            $this->db->where("service_plan_works_new.plan_id", $currentPlan);    
        }
        if(!empty($date)){   
            $this->db->where("service_plan_works_new.work_out_date", $date);     
        }
        $this->db->order_by('service_plan_works_new.id', 'DESC');       
        $getdata = $this->db->get();
        //echo $this->db->last_query(); //exit();
        $num     = $getdata->num_rows();    
        if($num){
           $data = $getdata->result();
           return $data;        
        }else{
            return false;
        }
    }
    public function getDietPlanDayNew($date='', $currentPlan='', $user_id=''){
        $this->db->select('diet_plan_works_new.*, service_plan_diet_items.item_title, service_plan_diet_items.cacalories, service_plan_diet_items.protein, service_plan_diet_items.fat, service_plan_diet_items.carbohydrate, service_plan_diet_items.fiber, service_plan_diet_items.suger, service_plan_diet_items.exercise_pic, userPlans.goal_id, service_plan_diet_items.description, service_plan_diet_items.preparation, service_plan_diet_items.healthiness, service_plan_diet_items.exercise_pic');
        $this->db->from('diet_plan_works_new');     
        $this->db->join('service_plan_diet_items', 'diet_plan_works_new.item_id=service_plan_diet_items.id', 'left');
        $this->db->join('userPlans', 'diet_plan_works_new.plan_id=userPlans.id', 'left');  
        if(!empty($currentPlan)){
            $this->db->where("diet_plan_works_new.plan_id", $currentPlan);
        }  
        if($this->input->get('user_id')){           
            $this->db->where("userPlans.user_id", $this->input->get('user_id'));    
        }else if(!empty($user_id)){         
            $this->db->where("userPlans.user_id", $user_id);    
        }else{
            $this->db->where("userPlans.user_id", user_id());   
        }
        if(!empty($date)){   
            $this->db->where("diet_plan_works_new.diet_date", $date);     
        }
        $this->db->order_by('diet_plan_works_new.id', 'DESC');      
        $getdata = $this->db->get();
        //echo $this->db->last_query(); exit();
        $num     = $getdata->num_rows();    
        if($num){
           $data = $getdata->result();
           return $data;        
        }else{
            return false;
        }
    }
    public function get_recipe_blog_imagesAPi($userId='', $type=''){
        $this->db->select('recipe_blog_image.image_name, recipe_blog_image.coverImage, recipe_blog_image.id, recipe_blog_image.type, recipe_blog_image.description, recipe_blog_image.title');
        $this->db->from('recipe_blog_image');     
        $this->db->order_by('recipe_blog_image.id', 'DESC');   
        if(!empty($userId)){    
            $this->db->where("recipe_blog_image.user_id", $userId);     
        }   
        if(!empty($type)){  
            $this->db->where("recipe_blog_image.type", $type);  
        }
        $this->db->where("recipe_blog_image.status", 1);
        $getdata = $this->db->get();
        $num     = $getdata->num_rows();    
        if($num){
           $data = $getdata->result();
           return $data;        
        }else{
            return false;
        }   
    }
    public function getLikesBookMarkBlogImgesAPi($userId='',$type=''){
        if($type=='likes'){
            $this->db->select('recipe_blog_image.image_name, recipe_blog_image.coverImage, recipe_blog_image.id, recipe_blog_image.type, recipe_blog_image.description, recipe_blog_image.title');
            $this->db->from('likes');     
            $this->db->join('recipe_blog_image', 'likes.recipe_blog_image_id=recipe_blog_image.id', 'left');     
            if(!empty($userId)){    
                $this->db->where("likes.user_id", $userId);     
            }
            $this->db->order_by('likes.id', 'DESC');   
        }else if($type=='comments'){
            $this->db->select('recipe_blog_image.image_name, recipe_blog_image.coverImage, recipe_blog_image.id, recipe_blog_image.type, recipe_blog_image.description, recipe_blog_image.title, comments.comments');
            $this->db->from('comments');     
            $this->db->join('recipe_blog_image', 'comments.recipe_blog_image_id=recipe_blog_image.id', 'left');     
            if(!empty($userId)){    
                $this->db->where("comments.user_id", $userId);      
            }
            $this->db->where("comments.comments !=", '');   
            $this->db->order_by('comments.id', 'DESC');   
        }else{
            $this->db->select('recipe_blog_image.*');
            $this->db->from('bookmark');     
            $this->db->join('recipe_blog_image', 'bookmark.recipe_blog_image_id=recipe_blog_image.id', 'left');     
            if(!empty($userId)){    
                $this->db->where("bookmark.user_id", $userId);      
            }
            $this->db->order_by('bookmark.id', 'DESC');   
        }   
        $getdata = $this->db->get();
        $num     = $getdata->num_rows();    
        if($num){
           $data = $getdata->result();
           return $data;        
        }else{
            return false;
        }   
    }
    public function get_user_exercise($subcategory_id=''){
        $this->db->select('service_plan_user_exercise.*');
        $this->db->from('service_plan_user_exercise');              
        if(!empty($subcategory_id)){
            $where =  "service_plan_user_exercise.sub_category_id IN(".$subcategory_id.")";
            $this->db->where($where);
        }
        $this->db->order_by('service_plan_user_exercise.id', 'DESC');       
        $getdata = $this->db->get();
        $num     = $getdata->num_rows();    
        if($num){
           $data = $getdata->result();
           return $data;        
        }else{
            return false;
        }
    }
    /*public function get_hieght_id(){
        $this->db->select('height.*');
        $this->db->from('height');            
        if ($this->input->get('useMetricsSystemVal')==1&&$this->input->get('height')) {
            $this->db->where("hieght_cm", $this->input->get('height'));
        }else if ($this->input->get('height')) {
            $this->db->where("height_title", $this->input->get('height'));
        }
    }*/ 
    public function get_user_result($offSet=0, $perPage=0){
        //$hieght_id = $this->get_hieght_id();
        $this->db->select('goal_setter.*, users.profile_pic, users.user_name, (SELECT COUNT(*) FROM `rok_userPlans` WHERE rok_userPlans.goal_id = rok_goal_setter.id AND rok_userPlans.status = 1) as plan_counter');
        $this->db->from('goal_setter');             
        $this->db->join('users', 'goal_setter.user_id=users.id', 'left');   
        if ($this->input->get('height')) {
            $this->db->where("goal_setter.height >", ($this->input->get('height'))-1);
            $this->db->where("goal_setter.height <", ($this->input->get('height'))+1);
        }
        if ($this->input->get('weight')) {
            $this->db->where("goal_setter.wieght >", ($this->input->get('weight'))-1);
            $this->db->where("goal_setter.wieght <", ($this->input->get('weight'))+1);
        }
        if ($this->input->get('loseWeight')) {
            $this->db->where("goal_setter.loseWeight >", ($this->input->get('loseWeight'))-1);
            $this->db->where("goal_setter.loseWeight <", ($this->input->get('loseWeight'))+1);
        }
        if ($this->input->get('loseDay')) {
            $this->db->where("goal_setter.loseDay >", ($this->input->get('loseDay'))-1);
            $this->db->where("goal_setter.loseDay <", ($this->input->get('loseDay'))+1);
        }
        $this->db->where("goal_setter.status !=", 3);   
        $this->db->having("plan_counter >", 0); 
        $this->db->order_by("goal_setter.id", 'DESC');  
        if($offSet>=0 && $perPage>0){           
            $this->db->limit($perPage,$offSet);
            $getdata = $this->db->get();
            //echo $this->db->last_query(); exit(); 
            $num  = $getdata->num_rows();       
            if($num){
               $data = $getdata->result();
               return $data;        
            }else{
                return false;
            }       
        }else{
            $getdata = $this->db->get();
            return $getdata->num_rows();    
        }       
    }
    public function getFollowingUser($userId=''){
        $this->db->select("follow_request.*, users.user_name, users.profile_pic, users.id as user_id");
        $this->db->from('follow_request');     
        $this->db->join('users', 'follow_request.receiver_id = users.id', 'left');
        $this->db->where("follow_request.accepted_status", 1);     
        if(!empty($userId)){    
            $this->db->where("follow_request.sender_id", $userId);      
        }   
        $this->db->order_by('users.user_name', 'ASC');  
        $getdata = $this->db->get();
        $num     = $getdata->num_rows();    
        if($num){
           $data = $getdata->result();
           return $data;        
        }else{
            return false;
        }   
    }   
    public function getFollowerUser($userId=''){
        $this->db->select("follow_request.*, users.user_name, users.profile_pic, users.id as user_id");
        $this->db->from('follow_request');     
        $this->db->join('users', 'follow_request.sender_id = users.id', 'left');     
        $this->db->where("follow_request.accepted_status", 1);       
        if(!empty($userId)){    
            $this->db->where("follow_request.receiver_id", $userId);        
        }   
        $this->db->order_by('users.user_name', 'ASC');  
        $getdata = $this->db->get();        
        $num     = $getdata->num_rows();    
        if($num){
           $data = $getdata->result();
           return $data;        
        }else{
            return false;
        }
    }
    public function getFollowerUserReq(){
        $this->db->select("follow_request.*, users.user_name, users.profile_pic, users.id as user_id");
        $this->db->from('follow_request');     
        $this->db->join('users', 'follow_request.sender_id = users.id', 'left');     
        $this->db->where("follow_request.accepted_status", 4);       
        $this->db->where("follow_request.receiver_id", user_id());
        $this->db->order_by('users.user_name', 'ASC');  
        $getdata = $this->db->get();        
        $num     = $getdata->num_rows();    
        if($num){
           $data = $getdata->result();
           return $data;        
        }else{
            return false;
        }
    }
    public function getUserLikes($countr=''){
        $this->db->select("likes.*, users.user_name, users.profile_pic, recipe_blog_image.title, recipe_blog_image.type,  recipe_blog_image.image_name");
        $this->db->from('likes');     
        $this->db->join('users', 'likes.user_id = users.id', 'left');     
        $this->db->join('recipe_blog_image', 'likes.recipe_blog_image_id = recipe_blog_image.id', 'left');     
        $this->db->where("recipe_blog_image.status", 1);         
        $this->db->where("recipe_blog_image.user_id", user_id());
        $this->db->order_by('likes.id', 'DESC');  
        $getdata = $this->db->get();        
        $num     = $getdata->num_rows();    
        if($num){
            if(!empty($countr)){
                return $getdata->num_rows();
            }else{
                return $getdata->result();
            }       
        }else{
            return false;
        }
    }
    public function getUserComments($countr=''){
        $this->db->select("comments.*, recipe_blog_image.title, recipe_blog_image.type,  recipe_blog_image.image_name");
        $this->db->from('comments');        
        $this->db->join('recipe_blog_image', 'comments.recipe_blog_image_id = recipe_blog_image.id', 'left');     
        $this->db->where("comments.status", 1);      
        $this->db->where("recipe_blog_image.status", 1);         
        $this->db->where("comments.user_id", user_id());
        $this->db->order_by('comments.id', 'ASC');  
        $getdata = $this->db->get();        
        $num     = $getdata->num_rows();    
        if($num){
            if(!empty($countr)){
                return $getdata->num_rows();
            }else{
                return $getdata->result();
            }       
        }else{
            return false;
        }
    }
    public function getCurrentPlanDetails($planType=''){
        $this->db->select("userPlans.*");
        $this->db->from('userPlans');  
        if($this->input->get('user_id')){
            $this->db->where("userPlans.user_id", $this->input->get('user_id'));
        }else{
            $this->db->where("userPlans.user_id", user_id());
        }        
        $this->db->where("userPlans.planType", $planType);
        $this->db->where("userPlans.activatePlan", '1');        
        $this->db->where("userPlans.status", '1');      
        $this->db->order_by('userPlans.id', 'DESC');  
        $getdata = $this->db->get();        
        $num     = $getdata->num_rows();    
        if($num){
            return $getdata->row();     
        }else{
            return false;
        }
    }
    public function get_cal_burned($date='', $user_id=''){
        $this->db->select("service_plan_user_exercise.cacalories, service_plan_user_exercise.measureUnit, service_plan_works_new.minuts");
        $this->db->from('workout_exercise_done');            
        $this->db->join("service_plan_works_new", 'workout_exercise_done.workout_exercise_id = service_plan_works_new.id', 'left');
        $this->db->join("service_plan_user_exercise", 'service_plan_works_new.exercise_id = service_plan_user_exercise.id', 'left');
        $this->db->where("workout_exercise_done.exercise_date", $date);
        if($this->input->get('user_id')){
            $this->db->where("service_plan_works_new.user_id", $this->input->get('user_id'));
        }else if(!empty($user_id)){
            $this->db->where("service_plan_works_new.user_id", $user_id);
        }else{
            $this->db->where("service_plan_works_new.user_id", user_id());
        } 
        $getdata     = $this->db->get();    
        //echo $this->db->last_query(); exit(); 
        $num         = $getdata->num_rows();    
        $Tcacalories = $Tminuts = 0;
        if($num){
            $datas = $getdata->result();
            if(!empty($datas)){
                foreach($datas as $data){
                    if($data->measureUnit==1){                      
                        $cacalories   = ($data->cacalories*$data->minuts)/60; 
                        $Tcacalories  = $Tcacalories + $cacalories;
                    }
                }
            }
            return round($Tcacalories);
        }else{
            return 0;
        }
    }
    public function get_cal_consumed($date='', $user_id=''){
        $this->db->select("service_plan_diet_items.cacalories, diet_plan_works_new.serving");
        $this->db->from('diet_plan_take_food');          
        $this->db->join("diet_plan_works_new", 'diet_plan_take_food.diet_food_id = diet_plan_works_new.id', 'left');
        $this->db->join("service_plan_diet_items", 'diet_plan_works_new.item_id = service_plan_diet_items.id', 'left');
        $this->db->join("userPlans", 'diet_plan_works_new.plan_id = userPlans.id', 'left');
        $this->db->where("diet_plan_take_food.food_taken_date", $date);
        if(!empty($user_id)){
            $this->db->where("diet_plan_works_new.user_id", $user_id);
        }else{
            $this->db->where("diet_plan_works_new.user_id", user_id());
        }
        $getdata = $this->db->get();
        $num     = $getdata->num_rows();    
        $cacalories = 0;
        if($num){
            $data = $getdata->result(); 
            if(!empty($data)){
                foreach($data as $dataRow){
                    $cacalories = ($dataRow->cacalories * $dataRow->serving) + $cacalories;
                }
                return $cacalories;
            }else{
                return 0;
            }       
        }else{
            return 0;
        }
    }
    public function get_avrage_by_month($strtdate='', $enddate='', $user_id=''){
        $this->db->select("*");
        $this->db->from('metricTracker'); 
        $this->db->where("user_id", $user_id);
        $this->db->where("matrixDate >=", $strtdate);
        $this->db->where("matrixDate <=", $enddate);
        $getdata = $this->db->get();        
        $num     = $getdata->num_rows();    
        $cacalories  = 0;
        $height = $weight = $calConsumed = $calBurned = $chest = $waist = $arms = $forearms = $legs = $calves= $hips = 0; 
        if($num){
            $datas = $getdata->result(); 
            if(!empty($datas)){
                foreach($datas as $data){
                    $heightNum = $weightNum = 0;
                    if(!empty($data->height)){
                        $heightNum = $data->height;
                        if(strpos($data->height, "'")>0){
                            $heightNum = str_replace("'", ".", $data->height);
                        }
                        if(strpos($heightNum, "cm")>0){
                            $heightNum = str_replace("cm", "", $heightNum);
                        }
                        if(strpos($heightNum, " ")>0){
                            $heightNum = str_replace(" ", "", $heightNum);
                        }
                        $heightNum = is_numeric($heightNum)?$heightNum:0;
                    }                   
                    if(!empty($data->weight)){
                        $weightNum = $data->weight;
                        if(strpos($data->weight, "lbs")>0){
                            $weightNum = str_replace("lbs", "", $data->weight);
                        }
                        if(strpos($weightNum, "kg")>0){
                            $weightNum = str_replace("kg", "", $weightNum);
                        }
                        if(strpos($weightNum, " ")>0){
                            $weightNum = str_replace(" ", "", $weightNum);
                        }
                        $weightNum = is_numeric($weightNum)?$weightNum:0;
                    }
                    $height         = $height + $heightNum;
                    $weight         = $weight + $weightNum;
                    $calConsumed    = $calConsumed + $data->calConsumed;
                    $calBurned      = $calBurned + $data->calBurned;
                    $chest          = $chest + $data->chest;
                    $waist          = $waist + $data->waist;
                    $arms           = $arms + $data->arms;
                    $forearms       = $forearms + $data->forearms;
                    $legs           = $legs + $data->legs;
                    $calves         = $calves + $data->calves;
                    $hips           = $hips + $data->hips;; 
                }
            } 
        }
        $argArr              = array();
        $argArr['height']    = !empty($weight)?($weight/date('t', $enddate)):0;        
        $argArr['weight']    = !empty($weight)?($weight/date('t', $enddate)):0;        
        $argArr['calBurned'] = !empty($calBurned)?($calBurned/date('t', $enddate)):0;        
        $argArr['chest']     = !empty($chest)?($chest/date('t', $enddate)):0;        
        $argArr['arms']      = !empty($arms)?($arms/date('t', $enddate)):0;        
        $argArr['forearms']  = !empty($forearms)?($forearms/date('t', $enddate)):0;        
        $argArr['legs']      = !empty($legs)?($legs/date('t', $enddate)):0;        
        $argArr['calves']    = !empty($calves)?($calves/date('t', $enddate)):0;        
        $argArr['hips']      = !empty($hips)?($hips/date('t', $enddate)):0;
        return $argArr;   
    }
    public function getDietPlanTakenTotals($food_taken_date='', $plan_id=''){
       $this->db->select('service_plan_diet_items.cacalories, service_plan_diet_items.protein, service_plan_diet_items.fat, service_plan_diet_items.carbohydrate, service_plan_diet_items.fiber, service_plan_diet_items.suger, diet_plan_works_new.serving');
        $this->db->from('diet_plan_take_food');     
        $this->db->join('diet_plan_works_new', 'diet_plan_take_food.diet_food_id=diet_plan_works_new.id', 'left');
        $this->db->join('service_plan_diet_items', 'diet_plan_works_new.item_id=service_plan_diet_items.id', 'left');
        $this->db->join('userPlans', 'diet_plan_works_new.plan_id=userPlans.id', 'left');
        if(!empty($food_taken_date)){   
            $this->db->where("diet_plan_take_food.food_taken_date", $food_taken_date);     
        }
        if(!empty($plan_id)){   
            $this->db->where("diet_plan_works_new.plan_id", $plan_id);     
        }
        $this->db->order_by('diet_plan_take_food.id', 'DESC');      
        $getdata = $this->db->get();
        //echo $this->db->last_query(); exit();
        $num     = $getdata->num_rows();    
        if($num){
           $data = $getdata->result();
           return $data;        
        }else{
            return false;
        }
    }
    public function getWorkOutExTotals($workout_ex_date='', $plan_id=''){
        $this->db->select('service_plan_user_exercise.cacalories, service_plan_user_exercise.measureUnit, service_plan_works_new.minuts, service_plan_works_new.sets, service_plan_works_new.reps');
        $this->db->from('workout_exercise_done');  
        $this->db->join('service_plan_works_new', 'workout_exercise_done.workout_exercise_id=service_plan_works_new.id', 'left');   
        $this->db->join('service_plan_user_exercise', 'service_plan_works_new.exercise_id=service_plan_user_exercise.id', 'left');
        $this->db->join('userPlans', 'service_plan_works_new.plan_id=userPlans.id', 'left');  
        if(!empty($workout_ex_date)){   
            $this->db->where("workout_exercise_done.exercise_date", $workout_ex_date);     
        }
        if(!empty($plan_id)){   
            $this->db->where("service_plan_works_new.plan_id", $plan_id);     
        }
        $this->db->order_by('service_plan_works_new.id', 'DESC');      
        $getdata = $this->db->get();
        //echo $this->db->last_query(); exit();
        $num     = $getdata->num_rows();    
        if($num){
           $data = $getdata->result();
           return $data;        
        }else{
            return false;
        }
    }
    public function get_diat_plan_edit_data($id='', $status=''){
        $this->db->select('diet_plan_works.*, service_plan_diet_items.item_title');
        $this->db->from('diet_plan_works');  
        $this->db->join('service_plan_diet_items', 'diet_plan_works.item_id=service_plan_diet_items.id', 'left');
        if($this->input->get('id')){   
            $this->db->where("diet_plan_works.category_id", $this->input->get('id'));     
        } 
        if(!empty($id)){   
            $this->db->where("diet_plan_works.id", $id);     
        }  
        $this->db->where("diet_plan_works.status", $status);     
        $getdata = $this->db->get();
        //echo $this->db->last_query(); exit();
        $num     = $getdata->num_rows();    
        if($num){
           $data = $getdata->result();
           return $data;        
        }else{
            return false;
        }
    }
}