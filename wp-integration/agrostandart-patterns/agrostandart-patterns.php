<?php
/**
 * Plugin Name: Агростандарт — блок-паттерны
 * Description: Дизайн-система и блок-паттерны Gutenberg для сайта АГРОСТАНДАРТ (шапка, герой, продукция, процесс, контакты, футер). Устанавливается как обычный плагин или mu-plugin, ничего не меняет в активной теме.
 * Version: 1.0.0
 * Author: Агростандарт
 * Text Domain: agrostandart-patterns
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

define( 'AS_PATTERNS_DIR', plugin_dir_path( __FILE__ ) );
define( 'AS_PATTERNS_URL', plugin_dir_url( __FILE__ ) );

/**
 * Подключаем фирменные шрифты и дизайн-систему.
 * Стили полностью заключены в класс .as-agrostandart (см. assets/agrostandart-design-system.css)
 * и не затрагивают остальную тему.
 */
function as_patterns_assets() {
	wp_enqueue_style(
		'agrostandart-fonts',
		'https://fonts.googleapis.com/css2?family=IBM+Plex+Mono:wght@400;500&family=Onest:wght@400;500;600&family=Rubik:wght@600;700;800&display=swap',
		array(),
		null
	);
	wp_enqueue_style(
		'agrostandart-design-system',
		AS_PATTERNS_URL . 'assets/agrostandart-design-system.css',
		array(),
		filemtime( AS_PATTERNS_DIR . 'assets/agrostandart-design-system.css' )
	);
}
add_action( 'wp_enqueue_scripts', 'as_patterns_assets' );
add_action( 'enqueue_block_editor_assets', 'as_patterns_assets' ); // те же стили в редакторе блоков

/**
 * Категория паттернов в инсерторе блоков.
 */
function as_patterns_category( $categories ) {
	return array_merge(
		array(
			array(
				'slug'  => 'agrostandart',
				'title' => 'Агростандарт',
			),
		),
		$categories
	);
}
add_filter( 'block_pattern_categories', 'as_patterns_category' );

/**
 * Регистрируем каждую секцию сайта как отдельный, независимый паттерн.
 * Порядковый префикс в имени файла (00, 01, 02…) — рекомендуемый порядок
 * сборки страницы, не более.
 */
function as_register_patterns() {
	$patterns = array(
		'00-header'   => 'Шапка сайта',
		'01-hero'     => 'Герой — заголовок и карточка продукта',
		'02-segments' => 'Кому поставляем — 4 карточки сегментов',
		'03-products' => 'Продукция по видам животных',
		'04-service'  => 'Технолог — блок сопровождения',
		'05-process'  => 'Как мы работаем — 6 шагов',
		'06-quality'  => 'Качество — чек-лист производства',
		'07-about'    => 'О компании',
		'08-cta-band' => 'CTA-полоса «Пришлите свой рацион»',
		'09-contacts' => 'Контакты и форма заявки',
		'10-footer'   => 'Футер сайта',
	);

	foreach ( $patterns as $file => $title ) {
		$path = AS_PATTERNS_DIR . 'patterns/' . $file . '.html';
		if ( ! file_exists( $path ) ) {
			continue;
		}
		register_block_pattern(
			'agrostandart/' . $file,
			array(
				'title'      => $title,
				'categories' => array( 'agrostandart' ),
				'content'    => file_get_contents( $path ),
			)
		);
	}
}
add_action( 'init', 'as_register_patterns' );
