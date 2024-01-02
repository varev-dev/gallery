<div class="centered">
    <?php if ($page != 1): ?>
        <form name="previous_page" method="get">
            <input type="number" name="page" value="<?=$page - 1?>" hidden="hidden"/>
            <input type="submit" class="page_btn" value="Previous">
        </form>
    <?php endif ?>
    <?php if ($page < $max_page): ?>
        <form name="next_page" method="get">
            <input type="number" name="page" value="<?=$page + 1?>" hidden="hidden"/>
            <input type="submit" class="page_btn" value="Next">
        </form>
    <?php endif ?>
</div>