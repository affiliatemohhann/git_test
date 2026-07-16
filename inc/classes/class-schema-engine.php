<?php 

// Enquee Assets @package WORTHIO

namespace WORTHIO_THEME\Inc;
use WORTHIO_THEME\Inc\Traits\Singleton;

class Schema_Engine {
    use Singleton;

    private $graph = [];
    private $schema_ids = [];

	protected function __construct() {	
		// Load class.
        add_action('wp', [$this, 'register_context'], 20);
        add_action('wp_footer', [$this, 'output_schema'], 20);
	}    

    public function add_schema($schema) {
        if (empty($schema) || !is_array($schema)) {
            return;
        }

        if (!empty($schema['@id'])) {
            $schema_id = (string) $schema['@id'];
            if (isset($this->schema_ids[$schema_id])) {
                return;
            }
            $this->schema_ids[$schema_id] = true;
        }

        $this->graph[] = $schema;
    }

    public function add_schemas($schemas) {
        if (!is_array($schemas)) {
            return;
        }

        foreach ($schemas as $schema) {
            $this->add_schema($schema);
        }
    }

    public function register_context() {
        if (is_admin()) {
            return;
        }

        $main_entity_id = null;
        $page_id = get_queried_object_id();

        if ($this->is_product_singular() && $page_id) {
            $main_entity_id = get_permalink($page_id) . '#product';
        } elseif (is_singular('comparison') && $page_id) {
            $main_entity_id = get_permalink($page_id) . '#comparison-itemlist';
        } elseif (is_tax('product_category') || is_category() || is_tag()) {
            $main_entity_id = Schema_Utils::current_url() . '#collection';
        }

        $this->add_schema(Site_Schema::website());
        $this->add_schema(Site_Schema::organization());
        $this->add_schema(Page_Schema::generate($main_entity_id));

        if ($this->is_product_singular()) {
            $post_id = get_queried_object_id();

            $this->add_schema(Product_Schema::generate($post_id));
            $this->add_schema(Review_Schema::generate($post_id));
        }

        if (is_singular('comparison')) {
            $post_id = get_queried_object_id();
            $product_ids = Schema_Utils::acf_get('compared_products', $post_id);

            $this->add_schema(
                Comparison_Schema::generate($product_ids, get_the_title($post_id), $post_id)
            );
        }

        if (is_tax('product_category')) {
            $term = get_queried_object();
            $this->add_schema(Category_Schema::generate($term));
        }

        $this->add_schema(Budget_ItemList_Schema::generate_for_current_page());
        $this->add_schema(Breadcrumb_Schema::generate());
        $this->add_schemas(apply_filters('worthio_schema_graph_nodes', [], $this->graph));
    }

    // Output the collected schema in the footer
    public function output_schema() {

        if (empty($this->graph)) {
            return;
        }

        echo '<script type="application/ld+json">';
        echo wp_json_encode([
            "@context" => "https://schema.org",
            "@graph"   => $this->graph
        ], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
        echo '</script>';
    }

    private function is_product_singular() {
        return is_singular(Schema_Utils::product_post_types());
    }
}

?>
