<?php

include 'components/connect.php';

if(isset($_COOKIE['user_id'])){
   $user_id = $_COOKIE['user_id'];
}else{
   $user_id = '';
}

if(isset($_POST['like_content'])){

if($user_id != ''){

   $content_id = $_POST['content_id'];
   $content_id = filter_var($content_id, FILTER_SANITIZE_STRING);

   $select_content = $conn->prepare("SELECT * FROM `content` WHERE id = ? LIMIT 1");
   $select_content->execute([$content_id]);
   $fetch_content = $select_content->fetch(PDO::FETCH_ASSOC);

   $tutor_id = $fetch_content['tutor_id'];

   $select_likes = $conn->prepare("SELECT * FROM `likes` WHERE user_id = ? AND content_id = ?");
   $select_likes->execute([$user_id, $content_id]);

   if($select_likes->rowCount() > 0){
      $remove_likes = $conn->prepare("DELETE FROM `likes` WHERE user_id = ? AND content_id = ?");
      $remove_likes->execute([$user_id, $content_id]);
      echo "Removed";
   }else{
      $insert_likes = $conn->prepare("INSERT INTO `likes`(user_id, tutor_id, content_id) VALUES(?,?,?)");
      $insert_likes->execute([$user_id, $tutor_id, $content_id]);
      echo "Added";
   }

}else{
   $message[] = 'please login first!';
}
}
?>