<?php
/**
 * Пустой шаблон страницы — без шапки/подвала/сайдбара активной темы.
 * Нужен потому, что паттерны плагина уже содержат свою полную шапку и
 * подвал (см. patterns/00-header.html, 01-footer.html) — тема поверх них
 * добавляла бы ещё один, свой собственный. Выводит только wp_head()/
 * wp_footer() (шрифты, стили, скрипты, admin-bar) и содержимое страницы.
 */

if ( ! defined( 'ABSPATH' ) ) exit;
?><!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
<meta charset="<?php bloginfo( 'charset' ); ?>">
<meta name="viewport" content="width=device-width, initial-scale=1">
<?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
<?php
while ( have_posts() ) :
	the_post();
	the_content();
endwhile;
?>
<?php wp_footer(); ?>
</body>
</html>
