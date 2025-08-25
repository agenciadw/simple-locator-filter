<?php
/**
 * Plugin Name: Simple Locator WooCommerce Filter
 * Plugin URI: https://github.com/agenciadw/simple-locator-filter
 * Description: Integra o Simple Locator com produtos do WooCommerce, permitindo filtrar produtos por localização e exibi-los em um mapa interativo.
 * Version: 0.1.0
 * Author: David William da Costa
 * Author URI: https://github.com/agenciadw
 * Text Domain: simple-locator-wc-filter
 * Domain Path: /languages
 * Requires at least: 5.0
 * Tested up to: 6.4
 * Requires PHP: 7.4
 * WC requires at least: 5.0
 * WC tested up to: 8.0
 * License: GPL v2 or later
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 */

// Prevenir acesso direto
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Função para obter imagem do produto com melhor qualidade
 */
function slwf_get_product_image_url($product_id, $size = 'slwf-product-square') {
    $image_url = '';
    $image_id = get_post_thumbnail_id($product_id);
    
    if ($image_id) {
        // Tentar obter imagem no tamanho especificado
        $image_url = wp_get_attachment_image_url($image_id, $size);
        
        // Se não existir, tentar outros tamanhos personalizados
        if (!$image_url) {
            $available_sizes = array('slwf-product-square', 'slwf-product-square-small', 'medium', 'medium_large', 'large', 'thumbnail');
            foreach ($available_sizes as $available_size) {
                $image_url = wp_get_attachment_image_url($image_id, $available_size);
                if ($image_url) {
                    break;
                }
            }
        }
        
        // Se ainda não encontrou, usar a imagem original
        if (!$image_url) {
            $image_url = wp_get_attachment_image_url($image_id, 'full');
        }
    }
    
    return $image_url;
}

// Definir constantes do plugin
define('SLWF_PLUGIN_URL', plugin_dir_url(__FILE__));
define('SLWF_PLUGIN_PATH', plugin_dir_path(__FILE__));
define('SLWF_PLUGIN_VERSION', '0.1.0');

/**
 * Classe principal do plugin
 */
class SimpleLocatorWooCommerceFilter {
    
    public function __construct() {
        add_action('init', array($this, 'init'));
        add_action('plugins_loaded', array($this, 'load_textdomain'));
    }
    
    /**
     * Inicializar o plugin
     */
    public function init() {
        // Verificar se WooCommerce está ativo
        if (!class_exists('WooCommerce')) {
            add_action('admin_notices', array($this, 'woocommerce_missing_notice'));
            return;
        }
        
        // Verificar se Simple Locator está ativo
        if (!class_exists('SimpleLocator')) {
            add_action('admin_notices', array($this, 'simple_locator_missing_notice'));
            return;
        }
        
        // Carregar funcionalidades
        $this->load_hooks();
    }
    
    /**
     * Carregar hooks e funcionalidades
     */
    public function load_hooks() {
        // Registrar shortcode
        add_shortcode('simple_locator_filter', array($this, 'simple_locator_products_shortcode'));
        
        // Adicionar campos de localização aos produtos
        add_action('woocommerce_product_options_general_product_data', array($this, 'add_location_fields_to_product'));
        add_action('woocommerce_process_product_meta', array($this, 'save_location_fields_for_product'));
        
        // Carregar Google Maps
        add_action('wp_enqueue_scripts', array($this, 'enqueue_google_maps'));
        
        // Carregar CSS do plugin
        add_action('wp_enqueue_scripts', array($this, 'enqueue_styles'));
        
        // Adicionar shortcode de debug
        add_shortcode('debug_product_meta', array($this, 'debug_product_meta_shortcode'));
        
        // Adicionar menu de administração
        add_action('admin_menu', array($this, 'add_admin_menu'));
        
        // Registrar configurações
        add_action('admin_init', array($this, 'register_settings'));
        
        // Registrar tamanhos de imagem personalizados
        add_action('after_setup_theme', array($this, 'add_custom_image_sizes'));
        
        // Adicionar notificação sobre regeneração de imagens
        add_action('admin_notices', array($this, 'regenerate_images_notice'));
    }
    
    /**
     * Carregar textdomain para traduções
     */
    public function load_textdomain() {
        load_plugin_textdomain('simple-locator-wc-filter', false, dirname(plugin_basename(__FILE__)) . '/languages');
    }
    
    /**
     * Aviso se WooCommerce não estiver ativo
     */
    public function woocommerce_missing_notice() {
        echo '<div class="notice notice-error"><p>';
        echo __('Simple Locator WooCommerce Filter requer o plugin WooCommerce para funcionar.', 'simple-locator-wc-filter');
        echo '</p></div>';
    }
    
    /**
     * Aviso se Simple Locator não estiver ativo
     */
    public function simple_locator_missing_notice() {
        echo '<div class="notice notice-error"><p>';
        echo __('Simple Locator WooCommerce Filter requer o plugin Simple Locator para funcionar.', 'simple-locator-wc-filter');
        echo '</p></div>';
    }
    
    /**
     * Shortcode principal
     */
    public function simple_locator_products_shortcode($atts) {
        // Definir atributos padrão
        $atts = shortcode_atts(array(
            'credenciais' => '', // Valor do filtro credenciais
            'category' => '', // Categoria de produto (opcional)
            'product_ids' => '',
            'map_height' => '400px',
            'show_list' => 'true',
            'zoom' => '10',
            'debug' => 'false' // Novo parâmetro para controlar debug
        ), $atts, 'simple_locator_filter');

        $debug_info = '';
        if ($atts['debug'] === 'true') {
            // DEBUG: Vamos verificar os valores disponíveis na taxonomia pa_credenciais
            $credenciais_terms = get_terms(array(
                'taxonomy' => 'pa_credenciais',
                'hide_empty' => false,
            ));
            
            $debug_info = '<div style="background: #f9f9f9; padding: 15px; margin: 10px 0; border: 1px solid #ddd; border-radius: 5px;">';
            $debug_info .= '<strong>DEBUG - Valores disponíveis em pa_credenciais:</strong><br>';
            if (!empty($credenciais_terms) && !is_wp_error($credenciais_terms)) {
                foreach ($credenciais_terms as $term) {
                    $debug_info .= 'Nome: "' . $term->name . '" | Slug: "' . $term->slug . '" | ID: ' . $term->term_id . '<br>';
                }
            } else {
                $debug_info .= 'Nenhum termo encontrado ou erro na taxonomia pa_credenciais<br>';
            }
            $debug_info .= '<strong>Valor procurado:</strong> "' . $atts['credenciais'] . '"<br>';
            $debug_info .= '</div>';
        }

        // Buscar produtos WooCommerce baseado nos filtros
        $product_args = array(
            'post_type' => 'product',
            'posts_per_page' => -1,
            'post_status' => 'publish',
            'tax_query' => array()
        );

        // Adicionar filtro por credenciais (atributo WooCommerce)
        if (!empty($atts['credenciais'])) {
            $product_args['tax_query'][] = array(
                'taxonomy' => 'pa_credenciais',
                'field' => 'name',
                'terms' => $atts['credenciais'],
                'operator' => 'IN'
            );
        }

        // Filtrar por categoria de produto se especificada
        if (!empty($atts['category'])) {
            $product_args['tax_query'][] = array(
                'taxonomy' => 'product_cat',
                'field' => 'slug',
                'terms' => $atts['category']
            );
        }

        // Se houver múltiplas taxonomias, definir relação AND
        if (count($product_args['tax_query']) > 1) {
            $product_args['tax_query']['relation'] = 'AND';
        }

        // Filtrar por IDs específicos se fornecidos
        if (!empty($atts['product_ids'])) {
            $product_ids = explode(',', $atts['product_ids']);
            $product_args['post__in'] = array_map('intval', $product_ids);
        }

        // Executar consulta
        $products = new WP_Query($product_args);
        
        if ($atts['debug'] === 'true') {
            // DEBUG: Informações sobre a consulta
            $debug_info .= '<div style="background: #e7f3ff; padding: 10px; margin: 10px 0; border: 1px solid #0073aa; border-radius: 5px;">';
            $debug_info .= '<strong>DEBUG - Consulta:</strong><br>';
            $debug_info .= 'Produtos encontrados: ' . $products->found_posts . '<br>';
            $debug_info .= 'SQL Query: ' . $products->request . '<br>';
            $debug_info .= '</div>';
        }
        
        if (!$products->have_posts()) {
            return $debug_info . '<p>' . __('Nenhum produto encontrado com os critérios especificados.', 'simple-locator-wc-filter') . '</p>';
        }

        // Preparar dados para o mapa
        $locations_data = array();
        $debug_products = array(); // Para debug
        
        while ($products->have_posts()) {
            $products->the_post();
            $product_id = get_the_ID();
            
            // Buscar dados de localização do produto - tentar múltiplos campos
            $latitude = null;
            $longitude = null;
            $address = '';
            
            // Tentar diferentes campos de latitude/longitude
            $lat_fields = array(
                '_simple_locator_lat',
                '_wp_geo_latitude', 
                'latitude',
                'lat',
                'wpmaps_lat'
            );
            
            $lng_fields = array(
                '_simple_locator_lng',
                '_wp_geo_longitude',
                'longitude', 
                'lng',
                'wpmaps_lng'
            );
            
            $address_fields = array(
                '_simple_locator_address',
                '_wp_geo_address',
                'address',
                'wpmaps_location'
            );
            
            // Buscar latitude
            foreach ($lat_fields as $field) {
                $lat_value = get_post_meta($product_id, $field, true);
                if (!empty($lat_value) && is_numeric($lat_value)) {
                    $latitude = floatval($lat_value);
                    break;
                }
            }
            
            // Buscar longitude
            foreach ($lng_fields as $field) {
                $lng_value = get_post_meta($product_id, $field, true);
                if (!empty($lng_value) && is_numeric($lng_value)) {
                    $longitude = floatval($lng_value);
                    break;
                }
            }
            
            // Buscar endereço
            foreach ($address_fields as $field) {
                $addr_value = get_post_meta($product_id, $field, true);
                if (!empty($addr_value) && $addr_value !== 'Array') {
                    $address = $addr_value;
                    break;
                }
            }
            
            // Se não tiver endereço, usar o título do produto
            if (empty($address)) {
                $address = get_the_title();
            }
            
            // DEBUG: Coletar informações do produto
            if ($atts['debug'] === 'true') {
                $debug_products[] = array(
                    'id' => $product_id,
                    'title' => get_the_title(),
                    'lat' => $latitude,
                    'lng' => $longitude,
                    'address' => $address,
                );
            }
            
            // Verificar se o produto tem coordenadas válidas
            if ($latitude !== null && $longitude !== null) {
                $product = wc_get_product($product_id);
                
                // Corrigir o problema do Array no preço
                $price_html = $product->get_price_html();
                if ($price_html === 'Array' || empty($price_html)) {
                    $price_html = '<span style="color: #353535;">' . __('Consulte o preço', 'simple-locator-wc-filter') . '</span>';
                }
                
                // Buscar imagem do produto com melhor qualidade
                $image_url = slwf_get_product_image_url($product_id, 'slwf-product-square');
                
                $locations_data[] = array(
                    'id' => $product_id,
                    'title' => get_the_title(),
                    'lat' => $latitude,
                    'lng' => $longitude,
                    'address' => $address,
                    'url' => get_permalink(),
                    'price' => $price_html,
                    'image' => $image_url
                );
            }
        }
        
        wp_reset_postdata();

        // Adicionar debug dos produtos encontrados
        if ($atts['debug'] === 'true') {
            $debug_info .= '<div style="background: #d4edda; padding: 10px; margin: 10px 0; border: 1px solid #28a745; border-radius: 5px;">';
            $debug_info .= '<strong>✅ DEBUG - Análise de cada produto:</strong><br>';
            foreach ($debug_products as $prod) {
                $debug_info .= '<strong>ID: ' . $prod['id'] . ' | Nome: "' . $prod['title'] . '"</strong><br>';
                $debug_info .= '&nbsp;&nbsp;Latitude: "' . $prod['lat'] . '" (vazio: ' . (empty($prod['lat']) ? 'SIM' : 'NÃO') . ')<br>';
                $debug_info .= '&nbsp;&nbsp;Longitude: "' . $prod['lng'] . '" (vazio: ' . (empty($prod['lng']) ? 'SIM' : 'NÃO') . ')<br>';
                $debug_info .= '&nbsp;&nbsp;Endereço: "' . $prod['address'] . '"<br>';
                $debug_info .= '&nbsp;&nbsp;Vai aparecer no mapa: ' . ($prod['lat'] !== null && $prod['lng'] !== null ? 'SIM' : 'NÃO') . '<br><br>';
            }
            $debug_info .= '<strong>Total de produtos no mapa: ' . count($locations_data) . '</strong><br>';
            $debug_info .= '</div>';
        }

        // Se não houver localizações, retornar mensagem
        if (empty($locations_data)) {
            return $debug_info . '<p>' . __('Nenhum produto com localização encontrado. Verifique se os produtos têm coordenadas de latitude e longitude configuradas.', 'simple-locator-wc-filter') . '</p>';
        }

        // Gerar ID único para o mapa
        $map_id = 'simple_locator_map_' . uniqid();
        
        ob_start();
        
        // Mostrar debug info se ativado
        if ($atts['debug'] === 'true') {
            echo $debug_info;
        }
        ?>
        
        <div id="<?php echo $map_id; ?>_container" class="simple-locator-products-container">
            <!-- CSS Crítico Inline - Estilos essenciais para renderização inicial -->
            <style>
                /* CSS Crítico - Carregamento imediato */
                .products-grid {
                    display: grid;
                    grid-template-columns: repeat(3, 1fr);
                    gap: 20px;
                    max-width: 100%;
                }
                .product-image-square {
                    width: 100%;
                    aspect-ratio: 1/1;
                    object-fit: cover;
                    margin-bottom: 15px;
                    border-radius: 5px;
                }
                @media (max-width: 768px) {
                    .products-grid {
                        grid-template-columns: repeat(2, 1fr) !important;
                    }
                }
                @media (max-width: 480px) {
                    .products-grid {
                        grid-template-columns: 1fr !important;
                    }
                }
            </style>
            
            <!-- Container do Mapa -->
            <div id="<?php echo $map_id; ?>" class="simple-locator-map" style="height: <?php echo $atts['map_height']; ?>;"></div>
            
            <?php if ($atts['show_list'] === 'true'): ?>
            <!-- Lista de produtos -->
            <div class="products-list">
                <h3><?php echo sprintf(__('Produtos Encontrados (%d)', 'simple-locator-wc-filter'), count($locations_data)); ?></h3>
                <div class="products-grid">
                    <?php foreach ($locations_data as $location): ?>
                    <div class="product-item">
                        <?php if ($location['image']): ?>
                        <img src="<?php echo $location['image']; ?>" alt="<?php echo esc_attr($location['title']); ?>" class="product-image-square">
                        <?php endif; ?>
                        <h4><a href="<?php echo $location['url']; ?>"><?php echo $location['title']; ?></a></h4>
                        <p class="price"><?php echo $location['price']; ?></p>
                        <p class="address"><?php echo $location['address']; ?></p>
                        <button onclick="focusMapLocation(<?php echo $location['lat']; ?>, <?php echo $location['lng']; ?>)">
                            <?php _e('Ver no Mapa', 'simple-locator-wc-filter'); ?>
                        </button>
                    </div>
                    <?php endforeach; ?>
                </div>
            </div>
            <?php endif; ?>
            
        </div>

        <script>
        // Aguardar carregamento do DOM
        document.addEventListener('DOMContentLoaded', function() {
            // Verificar se o Google Maps está carregado
            if (typeof google === 'undefined') {
                console.error('Google Maps não está carregado');
                document.getElementById('<?php echo $map_id; ?>').innerHTML = '<p style="padding: 20px; text-align: center;"><?php _e('Erro: Google Maps não está carregado. Verifique se a API key está configurada.', 'simple-locator-wc-filter'); ?></p>';
                return;
            }

            // Dados das localizações
            var locations = <?php echo json_encode($locations_data); ?>;
            
            // Definir centro padrão (São Paulo)
            var defaultCenter = {lat: -23.5505, lng: -46.6333};
            
            // Se houver localizações, usar a primeira como centro
            if (locations.length > 0) {
                defaultCenter = {lat: locations[0].lat, lng: locations[0].lng};
            }
            
            // Inicializar mapa
            var map = new google.maps.Map(document.getElementById('<?php echo $map_id; ?>'), {
                zoom: <?php echo intval($atts['zoom']); ?>,
                center: defaultCenter,
                mapTypeId: google.maps.MapTypeId.ROADMAP
            });

            // Criar bounds para ajustar zoom automaticamente
            var bounds = new google.maps.LatLngBounds();
            var markers = [];

            // Adicionar marcadores
            locations.forEach(function(location, index) {
                var marker = new google.maps.Marker({
                    position: {lat: location.lat, lng: location.lng},
                    map: map,
                    title: location.title,
                    animation: google.maps.Animation.DROP
                });

                // Criar infoWindow para cada marcador
                var infoWindow = new google.maps.InfoWindow({
                    content: `
                        <div style="max-width: 300px;">
                            ${location.image ? '<img src="' + location.image + '" class="map-popup-image">' : ''}
                            <h4 class="map-popup-title"><a href="${location.url}" target="_blank">${location.title}</a></h4>
                            <p class="map-popup-address"><strong><?php _e('Endereço:', 'simple-locator-wc-filter'); ?></strong> ${location.address}</p>
                            <a href="${location.url}" target="_blank" class="map-popup-button"><?php _e('Ver Produto', 'simple-locator-wc-filter'); ?></a>
                        </div>
                    `
                });

                // Adicionar listener para abrir infoWindow
                marker.addListener('click', function() {
                    // Fechar todas as infoWindows abertas
                    markers.forEach(function(m) {
                        if (m.infoWindow) {
                            m.infoWindow.close();
                        }
                    });
                    
                    infoWindow.open(map, marker);
                });

                // Armazenar referência da infoWindow no marker
                marker.infoWindow = infoWindow;
                markers.push(marker);
                
                // Estender bounds
                bounds.extend(marker.getPosition());
            });

            // Ajustar mapa para mostrar todos os marcadores
            if (locations.length > 1) {
                map.fitBounds(bounds);
            }

            // Função global para focar em uma localização específica
            window.focusMapLocation = function(lat, lng) {
                map.setCenter({lat: lat, lng: lng});
                map.setZoom(15);
                
                // Encontrar e abrir o marcador correspondente
                markers.forEach(function(marker) {
                    var pos = marker.getPosition();
                    if (Math.abs(pos.lat() - lat) < 0.0001 && Math.abs(pos.lng() - lng) < 0.0001) {
                        // Fechar todas as infoWindows
                        markers.forEach(function(m) {
                            if (m.infoWindow) {
                                m.infoWindow.close();
                            }
                        });
                        
                        // Abrir a infoWindow do marcador clicado
                        marker.infoWindow.open(map, marker);
                        
                        // Animação no marcador
                        marker.setAnimation(google.maps.Animation.BOUNCE);
                        setTimeout(function() {
                            marker.setAnimation(null);
                        }, 2000);
                    }
                });
            };
        });
        </script>

        <?php
        return ob_get_clean();
    }
    
    /**
     * Adicionar campos de localização ao produto
     */
    public function add_location_fields_to_product() {
        echo '<div class="options_group">';
        
        woocommerce_wp_text_input(array(
            'id' => '_simple_locator_lat',
            'label' => __('Latitude', 'simple-locator-wc-filter'),
            'placeholder' => 'Ex: -23.5505',
            'desc_tip' => 'true',
            'description' => __('Coordenada de latitude do produto', 'simple-locator-wc-filter')
        ));
        
        woocommerce_wp_text_input(array(
            'id' => '_simple_locator_lng',
            'label' => __('Longitude', 'simple-locator-wc-filter'),
            'placeholder' => 'Ex: -46.6333',
            'desc_tip' => 'true',
            'description' => __('Coordenada de longitude do produto', 'simple-locator-wc-filter')
        ));
        
        woocommerce_wp_text_input(array(
            'id' => '_simple_locator_address',
            'label' => __('Endereço', 'simple-locator-wc-filter'),
            'placeholder' => __('Endereço completo', 'simple-locator-wc-filter'),
            'desc_tip' => 'true',
            'description' => __('Endereço do produto para exibição', 'simple-locator-wc-filter')
        ));
        
        echo '</div>';
    }
    
    /**
     * Salvar campos de localização do produto
     */
    public function save_location_fields_for_product($post_id) {
        $latitude = $_POST['_simple_locator_lat'] ?? '';
        $longitude = $_POST['_simple_locator_lng'] ?? '';
        $address = $_POST['_simple_locator_address'] ?? '';
        
        if (!empty($latitude)) {
            update_post_meta($post_id, '_simple_locator_lat', sanitize_text_field($latitude));
        }
        if (!empty($longitude)) {
            update_post_meta($post_id, '_simple_locator_lng', sanitize_text_field($longitude));
        }
        if (!empty($address)) {
            update_post_meta($post_id, '_simple_locator_address', sanitize_text_field($address));
        }
    }
    
    /**
     * Carregar Google Maps
     */
    public function enqueue_google_maps() {
        if (!wp_script_is('google-maps')) {
            $api_key = get_option('slwf_google_maps_api_key', '');
            if ($api_key) {
                wp_enqueue_script('google-maps', 'https://maps.googleapis.com/maps/api/js?key=' . $api_key, array(), null, true);
            }
        }
    }
    
    /**
     * Carregar estilos CSS do plugin
     */
    public function enqueue_styles() {
        wp_enqueue_style(
            'simple-locator-wc-filter',
            SLWF_PLUGIN_URL . 'assets/css/simple-locator-wc-filter.css',
            array(),
            SLWF_PLUGIN_VERSION
        );
    }
    
    /**
     * Shortcode de debug
     */
    public function debug_product_meta_shortcode($atts) {
        $atts = shortcode_atts(array(
            'product_id' => 0
        ), $atts);
        
        if ($atts['product_id']) {
            ob_start();
            $this->debug_product_meta_fields($atts['product_id']);
            return ob_get_clean();
        }
        
        return __('ID do produto não especificado', 'simple-locator-wc-filter');
    }
    
    /**
     * Função para debug de meta campos
     */
    public function debug_product_meta_fields($product_id) {
        $all_meta = get_post_meta($product_id);
        echo '<h3>' . sprintf(__('Meta campos do produto ID: %d', 'simple-locator-wc-filter'), $product_id) . '</h3>';
        echo '<pre>';
        print_r($all_meta);
        echo '</pre>';
    }
    
    /**
     * Adicionar menu de administração
     */
    public function add_admin_menu() {
        add_options_page(
            __('Simple Locator WC Filter', 'simple-locator-wc-filter'),
            __('SL WC Filter', 'simple-locator-wc-filter'),
            'manage_options',
            'simple-locator-wc-filter',
            array($this, 'admin_page')
        );
    }
    
    /**
     * Página de administração
     */
    public function admin_page() {
        ?>
        <div class="wrap">
            <h1><?php _e('Simple Locator WooCommerce Filter', 'simple-locator-wc-filter'); ?></h1>
            
            <form method="post" action="options.php">
                <?php
                settings_fields('slwf_options');
                do_settings_sections('slwf_options');
                submit_button();
                ?>
            </form>
            
            <h2><?php _e('Como Usar', 'simple-locator-wc-filter'); ?></h2>
            <div class="card">
                <h3><?php _e('Shortcode Básico', 'simple-locator-wc-filter'); ?></h3>
                <code>[simple_locator_filter credenciais="Depósito de Jardim"]</code>
                
                <h3><?php _e('Shortcode com Debug', 'simple-locator-wc-filter'); ?></h3>
                <code>[simple_locator_filter credenciais="Depósito de Jardim" debug="true"]</code>
                
                <h3><?php _e('Shortcode Completo', 'simple-locator-wc-filter'); ?></h3>
                <code>[simple_locator_filter credenciais="Depósito de Jardim" category="jardim" map_height="500px" show_list="true" zoom="12"]</code>
            </div>
        </div>
        <?php
    }
    
    /**
     * Registrar configurações
     */
    public function register_settings() {
        register_setting('slwf_options', 'slwf_google_maps_api_key');
        
        add_settings_section(
            'slwf_main_section',
            __('Configurações Principais', 'simple-locator-wc-filter'),
            array($this, 'settings_section_callback'),
            'slwf_options'
        );
        
        add_settings_field(
            'slwf_google_maps_api_key',
            __('Google Maps API Key', 'simple-locator-wc-filter'),
            array($this, 'api_key_field_callback'),
            'slwf_options',
            'slwf_main_section'
        );
    }
    
    /**
     * Callback da seção de configurações
     */
    public function settings_section_callback() {
        echo '<p>' . __('Configure as opções do plugin Simple Locator WooCommerce Filter.', 'simple-locator-wc-filter') . '</p>';
    }
    
    /**
     * Callback do campo API Key
     */
    public function api_key_field_callback() {
        $api_key = get_option('slwf_google_maps_api_key', '');
        echo '<input type="text" name="slwf_google_maps_api_key" value="' . esc_attr($api_key) . '" class="regular-text" />';
        echo '<p class="description">' . __('Insira sua chave da API do Google Maps. Você pode obter uma em <a href="https://console.cloud.google.com/" target="_blank">Google Cloud Console</a>.', 'simple-locator-wc-filter') . '</p>';
    }
    
    /**
     * Adicionar tamanhos de imagem personalizados
     */
    public function add_custom_image_sizes() {
        // Tamanho quadrado para produtos (300x300px)
        add_image_size('slwf-product-square', 300, 300, true);
        
        // Tamanho quadrado menor para mobile (200x200px)
        add_image_size('slwf-product-square-small', 200, 200, true);
    }
    
    /**
     * Notificação sobre regeneração de imagens
     */
    public function regenerate_images_notice() {
        if (get_option('slwf_regenerate_images_notice', false)) {
            echo '<div class="notice notice-info is-dismissible">';
            echo '<p><strong>Simple Locator WooCommerce Filter:</strong> ';
            echo __('Para melhor qualidade das imagens quadradas, recomendamos regenerar as imagens dos produtos. Use um plugin como "Regenerate Thumbnails" para criar os novos tamanhos de imagem.', 'simple-locator-wc-filter');
            echo '</p>';
            echo '<p><a href="' . admin_url('plugin-install.php?s=regenerate+thumbnails&tab=search&type=term') . '" class="button button-primary">' . __('Instalar Regenerate Thumbnails', 'simple-locator-wc-filter') . '</a></p>';
            echo '</div>';
            
            // Remover a notificação após exibir
            delete_option('slwf_regenerate_images_notice');
        }
    }
}

// Inicializar o plugin
new SimpleLocatorWooCommerceFilter();

// Ativação do plugin
register_activation_hook(__FILE__, 'slwf_activate');
function slwf_activate() {
    // Criar opções padrão se não existirem
    if (!get_option('slwf_google_maps_api_key')) {
        add_option('slwf_google_maps_api_key', '');
    }
    
    // Registrar tamanhos de imagem personalizados
    add_image_size('slwf-product-square', 300, 300, true);
    add_image_size('slwf-product-square-small', 200, 200, true);
    
    // Notificar sobre regeneração de imagens
    add_option('slwf_regenerate_images_notice', true);
}

// Desativação do plugin
register_deactivation_hook(__FILE__, 'slwf_deactivate');
function slwf_deactivate() {
    // Limpar dados se necessário
}
?>
