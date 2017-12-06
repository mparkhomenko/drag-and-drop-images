<?php

        $name = $_POST['name'];
        $email = $_POST['email'];
        $phone = $_POST['phone'];
        $message = $_POST['message'];
        $message_part = "";

        $reg_name = "/^\D*$/";
        $reg_email = "/[a-z0-9][a-z0-9\._-]*[a-z0-9]*@([a-z0-9]+([a-z0-9-]*[a-z0-9]+)*\.)+[a-z]+/i";
        // $email = "max@gmail.com";

        // function removeDirectory($dir) {
        //         if ($objs = glob($dir."*")) {
        //                 foreach($objs as $obj) {
        //                         is_dir($obj) ? removeDirectory($obj) : unlink($obj);
        //                 }
        //         }
        //         rmdir($dir);
        // }
        
        if (preg_match($reg_email, $email) && preg_match($reg_name, $name) && !empty($_FILES['file'])) {
                $path = "/var/www/html/img/".$email."/";
                mkdir($path);

		$filePath = array();

                for($i = 0; $i < count($_FILES['file']['name']); $i++) {
                        if(!is_uploaded_file($_FILES['file']['tmp_name'][$i])) {
                                echo 'Файл не загружен';
                        } else {
                                array_push($filePath, $path.$_FILES['file']['name'][$i]);
                                move_uploaded_file($_FILES['file']['tmp_name'][$i], $filePath[$i]);
                        }
                }

                $to = "maksim.parkhomenko96@gmail.com";
                $subject = "Продажа битых авто";

                $boundary = "--" . md5(uniqid(time()));
                $headers = "MIME-Version: 1.0\n";
                $headers .= "Content-Type: multipart/mixed; boundary=\"$boundary\"\n";
                $headers .= "From: Autolom\n";
                $multipart = "--$boundary\n";
                $multipart .= "Content-Type: text/html; charset=utf-8\n";
                $multipart .= "Content-Transfer-Encoding: Quot-Printed\n\n";
                $multipart .= "<p><strong>Имя отправителя:</strong> $name</p>";
                $multipart .= "<p><strong>E-mail:</strong> $email</p>";
                $multipart .= "<p><strong>Телефон:</strong> $phone</p>";
                $multipart .= "<strong>Сообщение:</strong> $message\n\n";

                $message_part = '';

                foreach ($filePath as $key => $value) {
                        $tmp = explode('/', $value);
                        $name = $tmp[count($tmp)-1];

                        $fp = fopen($value, "r");
                        $file = fread($fp, filesize($value));

                        $message_part .= "--$boundary\n";
                        $message_part .= "Content-Type: application/octet-stream\n";
                        $message_part .= "Content-Transfer-Encoding: base64\n";
                        $message_part .= "Content-Disposition: attachment; filename=\"$name\"\n\n";
                        $message_part .= chunk_split(base64_encode($file)) . "\n";

                        // removeDirectory($path);
                }

                $multipart .= $message_part . "--$boundary--\n";
                if (mail ($to, $subject, $multipart, $headers)) {
                        echo "ok";
                }
        } else {
                echo "2";
        }
?>
