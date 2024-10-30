<?php
/* 
Plugin Name: Comment Reply Notifier
Plugin URI: http://leo108.com/
Version: 1.0
Author: leo108
Description: When someone reply a comment, the person who receive this reply will receive a mail.
Author URI: http://leo108.com/
*/

add_action('comment_post', 'comment');
function comment($comment_reply_id) 
{
    $comment = get_comment($comment_reply_id);
    if($comment->comment_parent != 0) 
    {
        $old_comment = get_comment($comment->comment_parent);
        if($old_comment->user_id == 0)
        {
            $email = $old_comment->comment_author_email;
            $name = $comment->comment_author;
            $content = $comment->comment_content;
            $post = get_post($comment->comment_post_ID);
            $title = $post->post_title;
            $link = get_permalink($comment->comment_post_ID);
            $blogname = wp_specialchars_decode(get_option('blogname'), ENT_QUOTES);
            $subject = sprintf('[%1$s] 评论回复: "%2$s"', $blogname, $title );
            $notify_message  = sprintf('你在《%s》的评论有新回复', $title ) . "\r\n";
            $notify_message .= sprintf( '用户名 : %1$s ', $name ) . "\r\n";
            $notify_message .= '评论内容: ' . "\r\n" . $content . "\r\n\r\n";
            $notify_message .= '您可在这里查看这篇文章的所有评论: ' . "\r\n";
            $notify_message .= $link . "#comments\r\n\r\n";
            $message_headers = "Content-Type: text/plain; charset=\"" . get_option('blog_charset') . "\"\n";
            wp_mail( $email, $subject, $notify_message, $message_headers );
        }
    } 
}
	

?>