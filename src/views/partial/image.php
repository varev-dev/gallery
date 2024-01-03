<a href="<?=$image["name"]."-wat.".$image["extension"]?>">
    <img class="gallery_image" src="<?=$image["name"]."-min.".$image["extension"]?>" alt="image"/>
</a>
<h4><?=$image['title']?></h4>
<p>Author: <span><?=$image['author']?></span></p>
<?php if ($image['visibility'] == 'private'):?>
    <p>PRIVATE</p>
<?php endif; ?>