<div class="commentInside" style="text-align: center;">
    <div class="box-support">
    <?php
    $loop = new WP_Query(array(
                'post_type' => 'support_online',
                'orderby' => 'meta_value_num',
                'order' => 'ASC',
                'meta_key' => 'so_order',
            ));
    $counter = 1;
    while ($loop->have_posts()) : $loop->the_post();
    ?>
    <h3 style="margin: 0 0 5px;"><?php the_title(); ?></h3>
    <p>
        <?php 
        $name = trim(get_post_meta(get_the_ID(), "so_name", true)); 
        $phone = trim(get_post_meta(get_the_ID(), "so_phone", true)); 
        $email = trim(get_post_meta(get_the_ID(), "so_email", true)); 
        $chat_type = trim(get_post_meta(get_the_ID(), "so_chat_type", true)); 
        $chat_id = trim(get_post_meta(get_the_ID(), "so_chat_id", true)); 
        
        if($name != "") echo "<p class='bold'>{$name}</p>";
        if($phone != "") echo "<p class='bold'>{$phone}</p>";
        
        echo '<script type="text/javascript" src="http://cdn.dev.skype.com/uri/skype-uri.js"></script>';
        
        switch ($chat_type) {
            case "yahoo":
                echo <<<HTML
                <a rel="nofollow" href="ymsgr:sendim?{$chat_id}">
                    <img border="0" src="http://opi.yahoo.com/online?u={$chat_id}&amp;m=g&amp;t=2" alt="{$chat_id}">
                </a>
HTML;
                break;
            case "skype":
                echo <<<HTML
<div id="SkypeButton_Dropdown_{$chat_id}_1">
  <script type="text/javascript">
    Skype.ui({
      "name": "dropdown",
      "element": "SkypeButton_Dropdown_{$chat_id}_1",
      "participants": ["{$chat_id}"],
      "imageSize": 32
    });
  </script>
</div>
HTML;
                break;
            default:
                break;
        }
        echo "<p><a href=\"mailto:{$email}\">{$email}</a></p>";
        ?>
    </p>
    <?php if ($counter != $loop->post_count): ?>
    <div class="line2" style="float: none; margin: 15px auto;"></div>
    <?php endif; ?>
    <?php $counter++; endwhile;?>
    </div>
</div>