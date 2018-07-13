<!DOCTYPE html>
<html>
<head>
    <title>Demo: Lazy Loader</title>
    <script src="https://code.jquery.com/jquery-3.2.1.min.js"></script>
    <style>
        #myScroll {
            border: 1px solid #999;
        }

        p {
            border: 1px solid #ccc;
            padding: 50px;
            text-align: center;
        }

        .loading {
            color: red;
        }
        .dynamic {
            background-color:#ccc;
            color:#000;
        }
    </style>
    <script>
		var counter=0;
        $(window).scroll(function () {
            if ($(window).scrollTop() == $(document).height() - $(window).height() && counter < 2) {
                appendData();
            }
        });
        function appendData() {
            var html = '';
            for (i = 0; i < 10; i++) {
                html += '<p class="dynamic">Dynamic Data :  This is test data.</br>Next line.</p>';
            }
            $('#myScroll').append(html);
			counter++;
			
			if(counter==2)
			$('#myScroll').append('<button id="uniqueButton" style="margin-left: 50%; background-color: powderblue;">Click</button></br></br>');
        }
    </script>
</head>
<body>
    <div id="myScroll">
        <p>
            Contents will load here!!!.<br />
        </p>
        <p >This is test data.</br>Next line.</p>
        <p >This is test data.</br>Next line.</p>
        <p >This is test data.</br>Next line.</p>
        <p >This is test data.</br>Next line.</p>
        <p >This is test data.</br>Next line.</p>
        <p >This is test data.</br>Next line.</p>
        <p >This is test data.</br>Next line.</p>
        <p >This is test data.</br>Next line.</p>
        <p >This is test data.</br>Next line.</p>
        <p >This is test data.</br>Next line.</p>
        <p >This is test data.</br>Next line.</p>
        <p >This is test data.</br>Next line.</p>
        <p >This is test data.</br>Next line.</p>
        <p >This is test data.</br>Next line.</p>
        <p >This is test data.</br>Next line.</p>
        <p >This is test data.</br>Next line.</p>
        <p >This is test data.</br>Next line.</p>
        <p >This is test data.</br>Next line.</p>
        <p >This is test data.</br>Next line.</p>
        <p >This is test data.</br>Next line.</p>
        <p >This is test data.</br>Next line.</p>
        <p >This is test data.</br>Next line.</p>
    </div>
</body>
</html>