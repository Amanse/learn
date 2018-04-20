<?php
class Notify{

  public static function MakeNotify($text = "5522545", $postid = 0){

    $text = explode(" ", $text);
    $notify = array();
    foreach ($text as $word) {
      if(substr($word, 0, 1) == "@"){
        $notify[substr($word, 1)] = array("type"=>1, "extra"=>'{ "postbody": "'.htmlentities(implode($text, " ")).'" }');
      }
    }
      if($text = "5522545" && $postid != 0){
          $temp = DB::query("SELECT posts.user_id AS reciever, post_likes.user_id AS sender FROM posts, post_likes
            WHERE posts.id = post_likes.post_id AND posts.id=:postid", array(":postid"=>$postid));
          $r = $temp[0]['reciever'];
          $s = $temp[0]['sender'];
					DB::query('INSERT INTO notification VALUES (\'\', :type, :reciever, :sender, :extra)', array(':type'=>2, ':reciever'=>$r, ':sender'=>$s, ":extra"=>""));
      }

    return $notify;

}
}
?>
