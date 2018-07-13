<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
class Common_model extends CI_Model {
    /*common funcation*/
    public function insert($table_name = '', $data = '') {
        $query = $this->db->insert($table_name, $data);
        if ($query) return $this->db->insert_id();
        else return FALSE;
    }
    function getAll($table) {
        $data = $this->db->get($table);
        $get = $data->result();
        $num = $data->num_rows();
        if ($num) {
            return $get;
        } else {
            return false;
        }
    }
    public function get_result($table_name = '', $id_array = '', $columns = array(), $order_by = array(), $limit = '', $group_by = '') {
        if (!empty($columns)):
            $all_columns = implode(",", $columns);
            $this->db->select($all_columns);
        endif;
        if (!empty($order_by)):
            $this->db->order_by($order_by[0], $order_by[1]);
        endif;
        if (!empty($group_by)):
            $this->db->group_by($group_by);
        endif;
        if (!empty($id_array)):
            foreach ($id_array as $key => $value) {
                $this->db->where($key, $value);
            }
        endif;
        if (!empty($limit)):
            $this->db->limit($limit);
        endif;
        $query = $this->db->get($table_name);
        if ($query->num_rows() > 0) return $query->result();
        else return FALSE;
    }
    public function get_row($table_name = '', $id_array = '', $columns = array(), $order_by = array(), $ctherWHERE = '') {
        if (!empty($columns)):
            $all_columns = implode(",", $columns);
            $this->db->select($all_columns);
        endif;
        if (!empty($id_array)):
            foreach ($id_array as $key => $value) {
                $this->db->where($key, $value);
            }
        endif;
        if (!empty($ctherWHERE)) {
            $this->db->where($ctherWHERE);
        }
        if (!empty($order_by)):
            $this->db->order_by($order_by[0], $order_by[1]);
        endif;
        $query = $this->db->get($table_name);
        if ($query->num_rows() > 0) return $query->row();
        else return FALSE;
    }
    public function get_row_With_orWhere($table_name = '', $id_array = '', $columns = array(), $order_by = array()) {
        if (!empty($columns)):
            $all_columns = implode(",", $columns);
            $this->db->select($all_columns);
        endif;
        if (!empty($id_array)):
            $count = 0;
            foreach ($id_array as $key => $value) {
                if ($count == 0) {
                    $this->db->where($key, $value);
                } else {
                    $this->db->or_where($key, $value);
                }
                $count++;
            }
        endif;
        if (!empty($order_by)):
            $this->db->order_by($order_by[0], $order_by[1]);
        endif;
        $query = $this->db->get($table_name);
        if ($query->num_rows() > 0) return $query->row();
        else return FALSE;
    }
    public function update($table_name = '', $data = '', $id_array = '') {
        if (!empty($id_array)):
            foreach ($id_array as $key => $value) {
                $this->db->where($key, $value);
            }
        endif;
        return $this->db->update($table_name, $data);
    }
    public function delete($table_name = '', $id_array = '') {
        return $this->db->delete($table_name, $id_array);
    }
    public function password_check($data = '') {
        $query = $this->db->get_where('admin_users', $data);
        if ($query->num_rows() > 0) return TRUE;
        else {
            return FALSE;
        }
    }
    public function getCustomer($offSet = 0, $perPage = 0) {
        $UserName = trim($this->input->get('customerName', TRUE));
        $userId = trim($this->input->get('customerId', TRUE));
        $email = trim($this->input->get('email', TRUE));
        $order = trim($this->input->get('order', TRUE));
        $this->db->select('users.*, (SELECT COUNT(*) FROM ' . TABLE_PRE . 'follow_request` WHERE `sender_id`=users.id) as followingCount, (SELECT COUNT(*) FROM ' . TABLE_PRE . 'follow_request` WHERE `receiver_id`=users.id) as followersCount');
        $this->db->from('users');
        if (isset($email) && !empty($email)) {
            $this->db->like('users.email', $email);
        }
        if (isset($UserName) && !empty($UserName)) {
            $search_term = trim($UserName);
            $j = 1;
            $keywords = explode(" ", preg_replace("/\s+/", " ", $search_term));
            $get_val = count($keywords);
            $wherelike = '';
            foreach ($keywords as $keyword) {
                if ($j == 1) {
                    $wherelike.= '(';
                }
                $wherelike.= '`users`.`first_name` LIKE "%' . trim($keyword) . '%"';
                if ($j < $get_val) {
                    $wherelike.= ' or ';
                } elseif ($j == $get_val) {
                    $wherelike.= ')';
                }
                $j++;
            }
            $like_condition = $wherelike;
            $this->db->where($like_condition);
        }
        if ($userId) {
            $this->db->where("`id`", $userId);
        }
        if (!empty($order)) {
            if ($order == 'NameAtoZ') {
                $this->db->order_by('users.first_name', 'ASC');
            } else if ($order == 'NameZtoA') {
                $this->db->order_by('users.first_name', 'DESC');
            } else if ($order == 'ASC') {
                $this->db->order_by('users.id', 'ASC');
            } else {
                $this->db->order_by('users.id', 'DESC');
            }
        } else {
            $this->db->order_by('users.id', 'DESC');
        }
        //$this->db->where('sks_user.isDeleted', 0);
        if ($offSet >= 0 && $perPage > 0) {
            $this->db->limit($perPage, $offSet);
            $getdata = $this->db->get();
            //echo $this->db->last_query(); exit();
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
    public function login($email, $password, $admin = '', $role_check = '', $proxy_login = '') {
        $email = trim($email);
        $password = trim($password);
        if ($admin == 1) {
            $data_array = array('email' => $email, 'user_role' => 1);
        } else {
            $data_array = array('email' => $email);
        }
        $query_get = $this->db->get_where('admin_users', $data_array);
        $count = $query_get->num_rows();
        $res = $query_get->row_array();
        $salt = $res['salt'];
        if ($count >= 1) {
            $this->db->select('*');
            $this->db->from('admin_users');
            $this->db->where('email', $email);
            if (!empty($admin) && $admin == 1) {
                $this->db->where('password', $password);
            } else {
                $this->db->where('password', sha1($salt . sha1($salt . sha1($password))));
            }
            $query = $this->db->get();
            //echo $this->db->last_query();
            $sql = $query;
            $check_count = $sql->num_rows();
            $result = $sql->row();
            if ($check_count == 1) {
                $user_data = array('id' => $sql->row()->id, 'user_role' => $sql->row()->user_role, 'first_name' => $sql->row()->first_name, 'last_name' => $sql->row()->last_name, 'email' => $sql->row()->email, 'image' => $sql->row()->image, 'logged_in' => TRUE);
                $this->session->set_userdata('superadmin_info', $user_data);
                $this->update('admin_users', array('last_ip' => $this->input->ip_address(), 'last_login' => date('Y-m-d h:i:s')), array('id' => $sql->row()->id));
                return TRUE;
            } else {
                return FALSE;
            }
        } else {
            $this->session->set_flashdata('msg_error', 'Incorrect Email.');
            return FALSE;
        }
    }
    public function front_user_login($email = '', $password = '', $directLogin = '') {
        $email = trim($email);
        $whereN = "(`email`='" . $email . "' OR `user_name`='" . $email . "')";
        $this->db->where($whereN);
        $query_get = $this->db->get('users');
        $count = $query_get->num_rows();
        $res = $query_get->row();
        if ($count >= 1) {
            if ($directLogin == 1) {
                $where = "`email`='" . $email . "'";
            } else {
                $salt = $res->salt;
                $newPassword = sha1($salt . sha1($salt . sha1($password)));
                $where = "((`email`='" . $email . "' AND `password`='" . $newPassword . "') OR (`user_name`='" . $email . "' AND `password`='" . $newPassword . "'))";
            }
            $this->db->select('*');
            $this->db->from('users');
            $this->db->where($where);
            $query = $this->db->get();
            $sql = $query;
            $check_count = $sql->num_rows();
            $result = $sql->row();
            if ($check_count == 1) {
                if ($result->is_email_verify == 1) {
                    if ($result->status == 1) {
                    	$iplinks = "http://www.geoplugin.net/php.gp?ip=" . $_SERVER['REMOTE_ADDR'];
                        $geoIP   = unserialize(file_get_contents($iplinks));                        
                        $current_location = '';
                        if (!empty($geoIP)) {
                            if (!empty($geoIP['geoplugin_city'])) $current_location.= $geoIP['geoplugin_city'];
                            if (!empty($geoIP['geoplugin_region'])) $current_location.= ', ' . $geoIP['geoplugin_region'];
                            if (!empty($geoIP['geoplugin_countryName'])) $current_location.= ', ' . $geoIP['geoplugin_countryName'];
                        }
                        $user_data = array('id' => $result->id, 'first_name' => $result->first_name, 'email' => $result->email, 'logged_in' => TRUE);
                        $this->session->set_userdata('user_info', $user_data);
                        $updated_address = array('last_ip' => $this->input->ip_address(), 'last_login' => date('Y-m-d h:i:s'));
                        $updated_address['last_location'] = $current_location;
                        $this->update('users', $updated_address, array('id' => $result->id));

                        return 1;
                    } else {
                        return 2;
                    }
                } else {
                    return 3;
                }
            } else {
                return 4;
            }
        } else {
            return 5;
        }
    }
    public function gmail_login($name, $email, $verified_email, $link, $picture, $gender, $locale) {
        $this->db->select('*');
        $this->db->from('users');
        $this->db->where('email', $email);
        $getdata = $this->db->get();
        $num = $getdata->num_rows();
        $geoIP = unserialize(file_get_contents("http://www.geoplugin.net/php.gp?ip=" . $_SERVER['REMOTE_ADDR']), true);
        $current_location = '';
        $current_location = '';
        if (!empty($geoIP)) {
            if (!empty($geoIP['geoplugin_city'])) $current_location.= $geoIP['geoplugin_city'];
            if (!empty($geoIP['geoplugin_region'])) $current_location.= ', ' . $geoIP['geoplugin_region'];
            if (!empty($geoIP['geoplugin_countryName'])) $current_location.= ', ' . $geoIP['geoplugin_countryName'];
        }
        if ($num > 0) {
            $result = $getdata->row();
            $user_data = array('id' => $result->id, 'first_name' => $result->first_name, 'email' => $result->email, 'logged_in' => TRUE);
            $this->session->set_userdata('user_info', $user_data);
            $this->update('users', array('last_ip' => $this->input->ip_address(), 'last_location' => $current_location, 'last_login' => date('Y-m-d h:i:s')), array('id' => $result->id));
            return 'already';
        } else {            
            $datas = array('email' => $email, 'is_email_verify' => $verified_email, 'created_date' => date("d M Y H:m:s A"), 'last_login' => date("d M Y H:m:s A"), 'login_status' => 1, 'register_type' => 3, 'last_location' => $current_location, 'last_ip' => $_SERVER['REMOTE_ADDR']);
            if (!empty($names)) {
                $datas['first_name'] = $names;
            }
            if (!empty($link)) {
                $datas['gmail_link'] = $link;
            }
            if (!empty($picture)) {
                $datas['file'] = $picture;
            }
            if (!empty($gender)) {
                $datas['gender'] = $gender;
            }
            $this->db->insert('users', $datas);
            $last_id = $this->db->insert_id();
            $user_data = array('id' => $last_id, 'first_name' => $name, 'last_name' => ' ', 'email' => $email, 'logged_in' => TRUE);
            $this->session->set_userdata('user_info', $user_data);
            return 'success';
        }
    }
    public function fb_login($name = " ", $email = " ", $id = " ") {
        $this->db->select('*');
        $this->db->from('users');
        $this->db->where('fb_ids', $id);
        $getdata = $this->db->get();
        $num = $getdata->num_rows();
        $geoIP = unserialize(file_get_contents("http://www.geoplugin.net/php.gp?ip=" . $_SERVER['REMOTE_ADDR']), true);
        $current_location = '';
        if (!empty($geoIP)) {
            if (!empty($geoIP['geoplugin_city'])) 			$current_location.= $geoIP['geoplugin_city'];
            if (!empty($geoIP['geoplugin_region'])) 		$current_location.= ', ' . $geoIP['geoplugin_region'];
            if (!empty($geoIP['geoplugin_countryName'])) 	$current_location.= ', ' . $geoIP['geoplugin_countryName'];
        }
        if ($num > 0) {
            $result = $getdata->row();
            $user_data = array('id' => $result->id, 'first_name' => $result->first_name, 'email' => $result->email, 'logged_in' => TRUE);
            $this->session->set_userdata('user_info', $user_data);
            $this->update('users', array('last_ip' => $this->input->ip_address(), 'last_login' => date('Y-m-d h:i:s'), 'last_location' => $current_location), array('id' => $result->id));
            return 'already';
        } else {
            if (!empty($names)) {
                $datas['first_name'] = $names;
            }
            $datas['email'] = $email;
            $datas['is_email_verify'] = 1;
            $datas['fb_ids'] = $id;
            $datas['created_date'] = date("d M Y H:m:s A");
            $datas['last_login'] = date("d M Y H:m:s A");
            $datas['login_status'] = 1;
            $datas['register_type'] = 2;
            $datas['last_location'] = $current_location;
            $datas['last_ip'] = $_SERVER['REMOTE_ADDR'];
            $this->db->insert('users', $datas);
            $last_id = $this->db->insert_id();
            $user_data = array('id' => $last_id, 'first_name' => $name, 'last_name' => ' ', 'email' => $email, 'logged_in' => TRUE);
            $this->session->set_userdata('user_info', $user_data);
            return 'success';
        }
    }
    /*product listing */
    public function getusers() {
        $UserName = $this->input->get('UserName');
        $this->db->select('customer.*');
        $this->db->from('customer');
        $this->db->where('status', 1);
        if (!empty($_GET['gender'])) {
            $this->db->where('gender', $this->input->get('gender'));
        }
        if (!empty($_GET['age'])) {
            $start = $_GET['age'] + 1;
            $startDif = strtotime("-" . $start . " year", time());
            $endDif = strtotime("-" . $_GET['age'] . " year", time());
            $this->db->where('dob >=', $startDif);
            $this->db->where('dob <=', $endDif);
        }
        if (isset($UserName) && !empty($UserName)) {
            $search_term = trim($UserName);
            $j = 1;
            $keywords = explode(" ", preg_replace("/\s+/", " ", $search_term));
            $get_val = count($keywords);
            $wherelike = '';
            foreach ($keywords as $keyword) {
                if ($j == 1) {
                    $wherelike.= '(';
                }
                $wherelike.= '`customer`.`first_name` LIKE "%' . trim($keyword) . '%"  or `customer`.`last_name` LIKE "%' . trim($keyword) . '%"';
                if ($j < $get_val) {
                    $wherelike.= ' or ';
                } elseif ($j == $get_val) {
                    $wherelike.= ')';
                }
                $j++;
            }
            $like_condition = $wherelike;
            $this->db->where($like_condition);
        }
        $query = $this->db->get();
        //echo $this->db->last_query();
        if ($query->num_rows() > 0) return $query->result();
        else return FALSE;
    }
    public function newslettor_model($offset = '', $per_page = '') {
        $this->db->from('newsletter');
        if (!empty($_GET['email'])) {
            if (!empty($_GET['email'])) {
                $this->db->like('email', trim($this->input->get('email', TRUE)));
            }
            if (!empty($_GET['user_id'])) {
                $this->db->where('id', trim($this->input->get('user_id', TRUE)));
            }
            $order = trim($this->input->get('order', TRUE));
            if (!empty($order)) {
                if ($order == 'new') {
                    $this->db->order_by('newsletter.id', 'DESC');
                } else if ($order == 'old') {
                    $this->db->order_by('newsletter.id', 'ASC');
                } else {
                    $this->db->order_by('newsletter.id', 'DESC');
                }
            }
        } else {
            $this->db->order_by('id', 'DESC');
        }
        if ($offset >= 0 && $per_page > 0) {
            $this->db->limit($per_page, $offset);
            $query = $this->db->get();
            if ($query->num_rows() > 0) return $query->result();
            else return FALSE;
        } else {
            return $this->db->count_all_results();
        }
    }
    public function contactus_model($offset = '', $per_page = '') {
        $this->db->from('contact_us');
        if (!empty($_GET)) {
            if (!empty($_GET['name'])) {
                $this->db->like('name', trim($this->input->get('name')));
            }
            if (!empty($_GET['email'])) {
                $this->db->like('email', trim($this->input->get('email', TRUE)));
            }
            if (!empty($_GET['user_id'])) {
                $this->db->where('id', trim($this->input->get('user_id', TRUE)));
            }
            if (!empty($_GET['status'])) {
                if ($_GET['status'] == 'unread') {
                    $this->db->where('read_status', 0);
                }
                if ($_GET['status'] == 'read') {
                    $this->db->where('read_status', 1);
                }
                if ($_GET['status'] == 'pending_message') {
                    $this->db->where('status', 0);
                }
                if ($_GET['status'] == 'reply_message') {
                    $this->db->where('status', 1);
                }
            }
            $order = $this->input->get('order');
            if (!empty($order)) {
                if ($order == 'new') {
                    $this->db->order_by('contact_us.id', 'DESC');
                } else if ($order == 'old') {
                    $this->db->order_by('contact_us.id', 'ASC');
                } else {
                    $this->db->order_by('contact_us.id', 'DESC');
                }
            }
        } else {
            $this->db->order_by('id', 'DESC');
        }
        if ($offset >= 0 && $per_page > 0) {
            $this->db->limit($per_page, $offset);
            $query = $this->db->get();
            if ($query->num_rows() > 0) return $query->result();
            else return FALSE;
        } else {
            return $this->db->count_all_results();
        }
    }
}
