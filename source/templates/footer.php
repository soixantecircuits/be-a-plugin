</div><!--end container-->
<div id="footer"><?php
      wp_nav_menu(
        array(
          'walker'          => new Walker_Nav_Menu_Rdc(),
          'theme_location'  => 'nav-1',
          'container_id'    => 'navigation',
          'container_class' => 'clear',
          'menu_class'      => 'navbar',
          'depth'           => '2'
          )
        );
    ?>
</div><!--end footer-->
<?php wp_footer(); ?>
</body>
</html>