<div style="position: relative;">
    <script type="text/javascript">
        $(function () {
            $('.leftMenu').css('display', 'none');
            $('.catName').live('mouseenter', function () {
                $('.leftMenu').show();
                $('.leftMenu').addClass('menuHover');
            });
            $('.catName').live('mouseleave', function () {
                $('.leftMenu').hide();
                $('.leftMenu').removeClass('menuHover');
            });
            $('.leftMenu').live('mouseenter', function () {
                $('.leftMenu').show();
                $('.leftMenu').addClass('menuHover');
            });
            $('.leftMenu').live('mouseleave', function () {
                $('.leftMenu').hide();
                $('.leftMenu').removeClass('menuHover');
            });
        });
    </script>
    <div class="leftMenu" style="display: none;">
        <?php include 'left_menu.php'; ?>
    </div>
</div>