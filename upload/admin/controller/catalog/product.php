<?php
class ControllerCatalogProduct extends Controller {
    private $error = array();

    public function index() {
        $this->language->load('catalog/product');
        $this->document->setTitle($this->language->get('heading_title'));
        $this->load->model('catalog/product');

        $this->getList();
    }

    public function insert() {
        $this->language->load('catalog/product');
        $this->document->setTitle($this->language->get('heading_title'));
        $this->load->model('catalog/product');

        if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm()) {
            // Handle automatic parent category assignment
            if (isset($this->request->post['product_category'])) {
                $selected_categories = $this->request->post['product_category'];
                $all_categories = $selected_categories;
                
                foreach ($selected_categories as $category_id) {
                    $parent_categories = $this->getParentCategories($category_id);
                    $all_categories = array_merge($all_categories, $parent_categories);
                }
                
                $this->request->post['product_category'] = array_unique($all_categories);
            }
            
            $this->model_catalog_product->addProduct($this->request->post);
            $this->session->data['success'] = $this->language->get('text_success');
            
            $url = $this->getUrl();
            $this->redirect('index.php?route=catalog/product&token=' . $this->session->data['token'] . $url);
        }

        $this->getForm();
    }

    public function update() {
        $this->language->load('catalog/product');
        $this->document->setTitle($this->language->get('heading_title'));
        $this->load->model('catalog/product');

        if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm()) {
            // Handle automatic parent category assignment
            if (isset($this->request->post['product_category'])) {
                $selected_categories = $this->request->post['product_category'];
                $all_categories = $selected_categories;
                
                foreach ($selected_categories as $category_id) {
                    $parent_categories = $this->getParentCategories($category_id);
                    $all_categories = array_merge($all_categories, $parent_categories);
                }
                
                $this->request->post['product_category'] = array_unique($all_categories);
            }
            
            $this->model_catalog_product->editProduct($this->request->get['product_id'], $this->request->post);
            $this->session->data['success'] = $this->language->get('text_success');
            
            $url = $this->getUrl();
            $this->redirect('index.php?route=catalog/product&token=' . $this->session->data['token'] . $url);
        }

        $this->getForm();
    }

    public function delete() {
        $this->language->load('catalog/product');
        $this->document->setTitle($this->language->get('heading_title'));
        $this->load->model('catalog/product');

        if (isset($this->request->post['selected']) && $this->validateDelete()) {
            foreach ($this->request->post['selected'] as $product_id) {
                $this->model_catalog_product->deleteProduct($product_id);
            }
            $this->session->data['success'] = $this->language->get('text_success');
            
            $url = $this->getUrl();
            $this->redirect('index.php?route=catalog/product&token=' . $this->session->data['token'] . $url);
        }

        $this->getList();
    }

    public function copy() {
        $this->language->load('catalog/product');
        $this->document->setTitle($this->language->get('heading_title'));
        $this->load->model('catalog/product');

        if (isset($this->request->post['selected']) && $this->validateCopy()) {
            foreach ($this->request->post['selected'] as $product_id) {
                $this->model_catalog_product->copyProduct($product_id);
            }
            $this->session->data['success'] = $this->language->get('text_success');
            
            $url = $this->getUrl();
            $this->redirect('index.php?route=catalog/product&token=' . $this->session->data['token'] . $url);
        }

        $this->getList();
    }

    private function getUrl() {
        $url = '';
        
        if (isset($this->request->get['filter_name'])) {
            $url .= '&filter_name=' . urlencode(html_entity_decode($this->request->get['filter_name'], ENT_QUOTES, 'UTF-8'));
        }
        
        if (isset($this->request->get['filter_model'])) {
            $url .= '&filter_model=' . urlencode(html_entity_decode($this->request->get['filter_model'], ENT_QUOTES, 'UTF-8'));
        }
        
        if (isset($this->request->get['filter_price'])) {
            $url .= '&filter_price=' . $this->request->get['filter_price'];
        }
        
        if (isset($this->request->get['filter_cost'])) {
            $url .= '&filter_cost=' . $this->request->get['filter_cost'];
        }
        
        if (isset($this->request->get['filter_profit'])) {
            $url .= '&filter_profit=' . $this->request->get['filter_profit'];
        }
        
        if (isset($this->request->get['filter_category_path'])) {
            $url .= '&filter_category_path=' . $this->request->get['filter_category_path'];
        }
        
        if (isset($this->request->get['filter_quantity'])) {
            $url .= '&filter_quantity=' . $this->request->get['filter_quantity'];
        }
        
        if (isset($this->request->get['filter_status'])) {
            $url .= '&filter_status=' . $this->request->get['filter_status'];
        }
        
        if (isset($this->request->get['sort'])) {
            $url .= '&sort=' . $this->request->get['sort'];
        }
        
        if (isset($this->request->get['order'])) {
            $url .= '&order=' . $this->request->get['order'];
        }
        
        if (isset($this->request->get['page'])) {
            $url .= '&page=' . $this->request->get['page'];
        }
        
        return $url;
    }

    protected function getList() {
        // Filter parameters
        if (isset($this->request->get['filter_name'])) {
            $filter_name = $this->request->get['filter_name'];
        } else {
            $filter_name = null;
        }

        if (isset($this->request->get['filter_model'])) {
            $filter_model = $this->request->get['filter_model'];
        } else {
            $filter_model = null;
        }

        if (isset($this->request->get['filter_price'])) {
            $filter_price = $this->request->get['filter_price'];
        } else {
            $filter_price = null;
        }

        if (isset($this->request->get['filter_cost'])) {
            $filter_cost = $this->request->get['filter_cost'];
        } else {
            $filter_cost = null;
        }

        if (isset($this->request->get['filter_profit'])) {
            $filter_profit = $this->request->get['filter_profit'];
        } else {
            $filter_profit = null;
        }

        if (isset($this->request->get['filter_category_path'])) {
            $filter_category_path = $this->request->get['filter_category_path'];
        } else {
            $filter_category_path = null;
        }

        if (isset($this->request->get['filter_quantity'])) {
            $filter_quantity = $this->request->get['filter_quantity'];
        } else {
            $filter_quantity = null;
        }

        if (isset($this->request->get['filter_status'])) {
            $filter_status = $this->request->get['filter_status'];
        } else {
            $filter_status = null;
        }

        if (isset($this->request->get['sort'])) {
            $sort = $this->request->get['sort'];
        } else {
            $sort = 'pd.name';
        }

        if (isset($this->request->get['order'])) {
            $order = $this->request->get['order'];
        } else {
            $order = 'ASC';
        }

        if (isset($this->request->get['page'])) {
            $page = $this->request->get['page'];
        } else {
            $page = 1;
        }

        $url = $this->getUrl();

        // Set breadcrumbs
        $this->data['breadcrumbs'] = array();
        $this->data['breadcrumbs'][] = array(
            'text'      => $this->language->get('text_home'),
            'href'      => $this->url->link('common/home', 'token=' . $this->session->data['token'], 'SSL'),
            'separator' => false
        );
        $this->data['breadcrumbs'][] = array(
            'text'      => $this->language->get('heading_title'),
            'href'      => $this->url->link('catalog/product', 'token=' . $this->session->data['token'] . $url, 'SSL'),
            'separator' => ' :: '
        );

        $this->data['insert'] = $this->url->link('catalog/product/insert', 'token=' . $this->session->data['token'] . $url, 'SSL');
        $this->data['copy'] = $this->url->link('catalog/product/copy', 'token=' . $this->session->data['token'] . $url, 'SSL');
        $this->data['delete'] = $this->url->link('catalog/product/delete', 'token=' . $this->session->data['token'] . $url, 'SSL');

        $filter_data = array(
            'filter_name'          => $filter_name,
            'filter_model'         => $filter_model,
            'filter_price'         => $filter_price,
            'filter_cost'          => $filter_cost,
            'filter_profit'        => $filter_profit,
            'filter_category_path' => $filter_category_path,
            'filter_quantity'      => $filter_quantity,
            'filter_status'        => $filter_status,
            'sort'                 => $sort,
            'order'                => $order,
            'start'                => ($page - 1) * $this->config->get('config_admin_limit'),
            'limit'                => $this->config->get('config_admin_limit')
        );

        $product_total = $this->model_catalog_product->getTotalProducts($filter_data);
        $results = $this->model_catalog_product->getProducts($filter_data);

        $this->data['products'] = array();
        $this->load->model('tool/image');

        foreach ($results as $result) {
            // Get full category paths
            $category_paths = $this->model_catalog_product->getProductCategoryPaths($result['product_id']);
            $categories = array();
            foreach ($category_paths as $path) {
                if (!empty($path['path'])) {
                    $categories[] = $path['path'];
                }
            }
            
            $profit = $result['price'] - $result['cost'];

            if ($result['image'] && file_exists(DIR_IMAGE . $result['image'])) {
                $image = $this->model_tool_image->resize($result['image'], 40, 40);
            } else {
                $image = $this->model_tool_image->resize('no_image.jpg', 40, 40);
            }

            $action = array();
            $action[] = array(
                'text' => $this->language->get('text_edit'),
                'href' => $this->url->link('catalog/product/update', 'token=' . $this->session->data['token'] . '&product_id=' . $result['product_id'] . $url, 'SSL')
            );

            $this->data['products'][] = array(
                'product_id' => $result['product_id'],
                'name'       => $result['name'],
                'model'      => $result['model'],
                'price'      => $this->currency->format($result['price']),
                'cost'       => $this->currency->format($result['cost']),
                'profit'     => $this->currency->format($profit),
                'profit_raw' => $profit,
                'cost_raw'   => $result['cost'],
                'categories' => implode('<br>', $categories),
                'quantity'   => $result['quantity'],
                'status'     => ($result['status'] ? $this->language->get('text_enabled') : $this->language->get('text_disabled')),
                'image'      => $image,
                'selected'   => isset($this->request->post['selected']) && in_array($result['product_id'], $this->request->post['selected']),
                'action'     => $action
            );
        }

        // Language strings
        $this->data['heading_title'] = $this->language->get('heading_title');
        $this->data['text_enabled'] = $this->language->get('text_enabled');
        $this->data['text_disabled'] = $this->language->get('text_disabled');
        $this->data['text_no_results'] = $this->language->get('text_no_results');
        $this->data['text_image_manager'] = $this->language->get('text_image_manager');
        $this->data['text_select_all'] = $this->language->get('text_select_all');
        $this->data['text_unselect_all'] = $this->language->get('text_unselect_all');
        $this->data['text_pagination'] = $this->language->get('text_pagination');
        
        // Column text
        $this->data['column_image'] = $this->language->get('column_image');
        $this->data['column_name'] = $this->language->get('column_name');
        $this->data['column_model'] = $this->language->get('column_model');
        $this->data['column_price'] = $this->language->get('column_price');
        $this->data['column_cost'] = $this->language->get('column_cost');
        $this->data['column_profit'] = $this->language->get('column_profit');
        $this->data['column_category'] = $this->language->get('column_category');
        $this->data['column_quantity'] = $this->language->get('column_quantity');
        $this->data['column_status'] = $this->language->get('column_status');
        $this->data['column_action'] = $this->language->get('column_action');
        
        // Button text
        $this->data['button_copy'] = $this->language->get('button_copy');
        $this->data['button_insert'] = $this->language->get('button_insert');
        $this->data['button_delete'] = $this->language->get('button_delete');
        $this->data['button_filter'] = $this->language->get('button_filter');
        $this->data['button_reset'] = $this->language->get('button_reset');
        $this->data['text_select'] = $this->language->get('text_select');

        $this->data['token'] = $this->session->data['token'];

        // Error handling
        if (isset($this->error['warning'])) {
            $this->data['error_warning'] = $this->error['warning'];
        } else {
            $this->data['error_warning'] = '';
        }

        if (isset($this->session->data['success'])) {
            $this->data['success'] = $this->session->data['success'];
            unset($this->session->data['success']);
        } else {
            $this->data['success'] = '';
        }

        // Sort URLs - Fixed with proper toggle functionality
        $filter_url = '';
        if (isset($this->request->get['filter_name'])) {
            $filter_url .= '&filter_name=' . urlencode(html_entity_decode($this->request->get['filter_name'], ENT_QUOTES, 'UTF-8'));
        }
        if (isset($this->request->get['filter_model'])) {
            $filter_url .= '&filter_model=' . urlencode(html_entity_decode($this->request->get['filter_model'], ENT_QUOTES, 'UTF-8'));
        }
        if (isset($this->request->get['filter_price'])) {
            $filter_url .= '&filter_price=' . $this->request->get['filter_price'];
        }
        if (isset($this->request->get['filter_cost'])) {
            $filter_url .= '&filter_cost=' . $this->request->get['filter_cost'];
        }
        if (isset($this->request->get['filter_profit'])) {
            $filter_url .= '&filter_profit=' . $this->request->get['filter_profit'];
        }
        if (isset($this->request->get['filter_category_path'])) {
            $filter_url .= '&filter_category_path=' . $this->request->get['filter_category_path'];
        }
        if (isset($this->request->get['filter_quantity'])) {
            $filter_url .= '&filter_quantity=' . $this->request->get['filter_quantity'];
        }
        if (isset($this->request->get['filter_status'])) {
            $filter_url .= '&filter_status=' . $this->request->get['filter_status'];
        }
        if (isset($this->request->get['page'])) {
            $filter_url .= '&page=' . $this->request->get['page'];
        }
        
        // Create sorting URLs with toggle functionality
        $this->data['sort_name'] = $this->getSortUrl('pd.name', $filter_url);
        $this->data['sort_model'] = $this->getSortUrl('p.model', $filter_url);
        $this->data['sort_price'] = $this->getSortUrl('p.price', $filter_url);
        $this->data['sort_cost'] = $this->getSortUrl('p.cost', $filter_url);
        $this->data['sort_profit'] = $this->getSortUrl('profit', $filter_url);
        $this->data['sort_quantity'] = $this->getSortUrl('p.quantity', $filter_url);
        $this->data['sort_status'] = $this->getSortUrl('p.status', $filter_url);

        // Pagination
        $pagination = new Pagination();
        $pagination->total = $product_total;
        $pagination->page = $page;
        $pagination->limit = $this->config->get('config_admin_limit');
        $pagination->text = $this->language->get('text_pagination');
        $pagination->url = $this->url->link('catalog/product', 'token=' . $this->session->data['token'] . $filter_url . '&page={page}', 'SSL');

        $this->data['pagination'] = $pagination->render();

        // Calculate showing results text
        if ($product_total) {
            $start = ($page - 1) * $this->config->get('config_admin_limit') + 1;
            $end = min($page * $this->config->get('config_admin_limit'), $product_total);
            $this->data['results'] = sprintf($this->language->get('text_pagination'), $start, $end, $product_total, ceil($product_total / $this->config->get('config_admin_limit')));
        } else {
            $this->data['results'] = '';
        }

        // Filter data for view
        $this->data['filter_name'] = $filter_name;
        $this->data['filter_model'] = $filter_model;
        $this->data['filter_price'] = $filter_price;
        $this->data['filter_cost'] = $filter_cost;
        $this->data['filter_profit'] = $filter_profit;
        $this->data['filter_quantity'] = $filter_quantity;
        $this->data['filter_status'] = $filter_status;

        // Get categories for dropdown filter with full paths
        $this->load->model('catalog/category');
        $this->data['categories'] = $this->getCategoriesTreeWithPath();
        $this->data['filter_category_path'] = $filter_category_path;

        $this->data['sort'] = $sort;
        $this->data['order'] = $order;
        $this->data['page'] = $page;

        $this->template = 'catalog/product_list.tpl';
        $this->children = array(
            'common/header',
            'common/footer'
        );

        $this->response->setOutput($this->render());
    }

    private function getSortUrl($sort_field, $url) {
        $order = 'ASC';
        if (isset($this->request->get['sort']) && $this->request->get['sort'] == $sort_field) {
            $order = (isset($this->request->get['order']) && $this->request->get['order'] == 'ASC') ? 'DESC' : 'ASC';
        }
        return $this->url->link('catalog/product', 'token=' . $this->session->data['token'] . '&sort=' . $sort_field . '&order=' . $order . $url, 'SSL');
    }

    protected function getForm() {
        // Load ALL language strings to avoid undefined variable errors
        $this->load->language('catalog/product');
        
        $this->data['heading_title'] = $this->language->get('heading_title');
        
        // Text strings
        $this->data['text_enabled'] = $this->language->get('text_enabled');
        $this->data['text_disabled'] = $this->language->get('text_disabled');
        $this->data['text_none'] = $this->language->get('text_none');
        $this->data['text_yes'] = $this->language->get('text_yes');
        $this->data['text_no'] = $this->language->get('text_no');
        $this->data['text_select'] = $this->language->get('text_select');
        $this->data['text_select_all'] = $this->language->get('text_select_all');
        $this->data['text_unselect_all'] = $this->language->get('text_unselect_all');
        $this->data['text_image_manager'] = $this->language->get('text_image_manager');
        $this->data['text_browse'] = $this->language->get('text_browse');
        $this->data['text_clear'] = $this->language->get('text_clear');
        $this->data['text_default'] = $this->language->get('text_default');
        
        // Entry strings - ALL fields loaded
        $this->data['entry_name'] = $this->language->get('entry_name');
        $this->data['entry_meta_keyword'] = $this->language->get('entry_meta_keyword');
        $this->data['entry_meta_description'] = $this->language->get('entry_meta_description');
        $this->data['entry_description'] = $this->language->get('entry_description');
        $this->data['entry_model'] = $this->language->get('entry_model');
        $this->data['entry_sku'] = $this->language->get('entry_sku');
        $this->data['entry_upc'] = $this->language->get('entry_upc');
        $this->data['entry_ean'] = $this->language->get('entry_ean');
        $this->data['entry_jan'] = $this->language->get('entry_jan');
        $this->data['entry_isbn'] = $this->language->get('entry_isbn');
        $this->data['entry_mpn'] = $this->language->get('entry_mpn');
        $this->data['entry_location'] = $this->language->get('entry_location');
        $this->data['entry_price'] = $this->language->get('entry_price');
        $this->data['entry_cost'] = $this->language->get('entry_cost');
        $this->data['entry_profit'] = $this->language->get('entry_profit');
        $this->data['entry_quantity'] = $this->language->get('entry_quantity');
        $this->data['entry_minimum'] = $this->language->get('entry_minimum');
        $this->data['entry_subtract'] = $this->language->get('entry_subtract');
        $this->data['entry_stock_status'] = $this->language->get('entry_stock_status');
        $this->data['entry_date_available'] = $this->language->get('entry_date_available');
        $this->data['entry_manufacturer'] = $this->language->get('entry_manufacturer');
        $this->data['entry_shipping'] = $this->language->get('entry_shipping');
        $this->data['entry_points'] = $this->language->get('entry_points');
        $this->data['entry_tax_class'] = $this->language->get('entry_tax_class');
        $this->data['entry_weight'] = $this->language->get('entry_weight');
        $this->data['entry_dimension'] = $this->language->get('entry_dimension');
        $this->data['entry_length'] = $this->language->get('entry_length');
        $this->data['entry_width'] = $this->language->get('entry_width');
        $this->data['entry_height'] = $this->language->get('entry_height');
        $this->data['entry_weight_class'] = $this->language->get('entry_weight_class');
        $this->data['entry_length_class'] = $this->language->get('entry_length_class');
        $this->data['entry_image'] = $this->language->get('entry_image');
        $this->data['entry_keyword'] = $this->language->get('entry_keyword');
        $this->data['entry_category'] = $this->language->get('entry_category');
        $this->data['entry_tag'] = $this->language->get('entry_tag');
        $this->data['entry_status'] = $this->language->get('entry_status');
        $this->data['entry_sort_order'] = $this->language->get('entry_sort_order');
        
        // Button text
        $this->data['button_save'] = $this->language->get('button_save');
        $this->data['button_cancel'] = $this->language->get('button_cancel');
        $this->data['button_reset'] = $this->language->get('button_reset');
        
        // Tab text
        $this->data['tab_general'] = $this->language->get('tab_general');
        $this->data['tab_data'] = $this->language->get('tab_data');
        $this->data['tab_links'] = $this->language->get('tab_links');
        
        $this->data['token'] = $this->session->data['token'];

        // Error handling
        if (isset($this->error['warning'])) {
            $this->data['error_warning'] = $this->error['warning'];
        } else {
            $this->data['error_warning'] = '';
        }

        if (isset($this->error['name'])) {
            $this->data['error_name'] = $this->error['name'];
        } else {
            $this->data['error_name'] = array();
        }

        if (isset($this->error['model'])) {
            $this->data['error_model'] = $this->error['model'];
        } else {
            $this->data['error_model'] = '';
        }

        // Set breadcrumbs for form
        $url = $this->getUrl();

        $this->data['breadcrumbs'] = array();
        $this->data['breadcrumbs'][] = array(
            'text'      => $this->language->get('text_home'),
            'href'      => $this->url->link('common/home', 'token=' . $this->session->data['token'], 'SSL'),
            'separator' => false
        );
        $this->data['breadcrumbs'][] = array(
            'text'      => $this->language->get('heading_title'),
            'href'      => $this->url->link('catalog/product', 'token=' . $this->session->data['token'] . $url, 'SSL'),
            'separator' => ' :: '
        );

        if (!isset($this->request->get['product_id'])) {
            $this->data['action'] = $this->url->link('catalog/product/insert', 'token=' . $this->session->data['token'] . $url, 'SSL');
        } else {
            $this->data['action'] = $this->url->link('catalog/product/update', 'token=' . $this->session->data['token'] . '&product_id=' . $this->request->get['product_id'] . $url, 'SSL');
        }

        $this->data['cancel'] = $this->url->link('catalog/product', 'token=' . $this->session->data['token'] . $url, 'SSL');

        if (isset($this->request->get['product_id']) && ($this->request->server['REQUEST_METHOD'] != 'POST')) {
            $product_info = $this->model_catalog_product->getProduct($this->request->get['product_id']);
        }

        $this->load->model('localisation/language');
        $this->data['languages'] = $this->model_localisation_language->getLanguages();

        if (isset($this->request->post['product_description'])) {
            $this->data['product_description'] = $this->request->post['product_description'];
        } elseif (isset($this->request->get['product_id'])) {
            $this->data['product_description'] = $this->model_catalog_product->getProductDescriptions($this->request->get['product_id']);
        } else {
            $this->data['product_description'] = array();
        }

        // Ensure all required fields exist in product_description array for each language
        foreach ($this->data['languages'] as $language) {
            if (!isset($this->data['product_description'][$language['language_id']])) {
                $this->data['product_description'][$language['language_id']] = array();
            }
            
            // Set default values for required fields
            $required_fields = array('name', 'meta_description', 'meta_keyword', 'description', 'tag');
            foreach ($required_fields as $field) {
                if (!isset($this->data['product_description'][$language['language_id']][$field])) {
                    $this->data['product_description'][$language['language_id']][$field] = '';
                }
            }
        }

        // Initialize ALL form fields with default values
        $form_fields = array(
            'model', 'sku', 'upc', 'ean', 'jan', 'isbn', 'mpn', 'location', 
            'price', 'cost', 'quantity', 'minimum', 'subtract', 'points',
            'weight', 'length', 'width', 'height', 'keyword',
            'status', 'sort_order', 'manufacturer_id', 'tax_class_id',
            'stock_status_id', 'date_available', 'weight_class_id',
            'length_class_id', 'shipping'
        );
        
        foreach ($form_fields as $field) {
            if (isset($this->request->post[$field])) {
                $this->data[$field] = $this->request->post[$field];
            } elseif (!empty($product_info) && isset($product_info[$field])) {
                $this->data[$field] = $product_info[$field];
            } else {
                // Set default values
                if ($field == 'price' || $field == 'cost') {
                    $this->data[$field] = '0.00';
                } elseif ($field == 'quantity') {
                    $this->data[$field] = 1;
                } elseif ($field == 'status') {
                    $this->data[$field] = 1;
                } elseif ($field == 'minimum') {
                    $this->data[$field] = 1;
                } elseif ($field == 'subtract') {
                    $this->data[$field] = 1;
                } elseif ($field == 'shipping') {
                    $this->data[$field] = 1;
                } elseif ($field == 'sort_order') {
                    $this->data[$field] = 0;
                } elseif ($field == 'date_available') {
                    $this->data[$field] = date('Y-m-d');
                } elseif ($field == 'stock_status_id') {
                    $this->data[$field] = 5;
                } elseif ($field == 'tax_class_id') {
                    $this->data[$field] = 0;
                } elseif ($field == 'manufacturer_id') {
                    $this->data[$field] = 0;
                } elseif ($field == 'weight_class_id') {
                    $this->data[$field] = 1;
                } elseif ($field == 'length_class_id') {
                    $this->data[$field] = 1;
                } elseif ($field == 'weight' || $field == 'length' || $field == 'width' || $field == 'height') {
                    $this->data[$field] = 0;
                } elseif ($field == 'points') {
                    $this->data[$field] = 0;
                } else {
                    $this->data[$field] = '';
                }
            }
        }

        // Load tax classes
        $this->load->model('localisation/tax_class');
        $this->data['tax_classes'] = $this->model_localisation_tax_class->getTaxClasses();

        // Load stock statuses
        $this->load->model('localisation/stock_status');
        $this->data['stock_statuses'] = $this->model_localisation_stock_status->getStockStatuses();

        // Load weight classes
        $this->load->model('localisation/weight_class');
        $this->data['weight_classes'] = $this->model_localisation_weight_class->getWeightClasses();

        // Load length classes
        $this->load->model('localisation/length_class');
        $this->data['length_classes'] = $this->model_localisation_length_class->getLengthClasses();

        // Categories
        $this->load->model('catalog/category');
        
        if (isset($this->request->post['product_category'])) {
            $categories = $this->request->post['product_category'];
        } elseif (isset($this->request->get['product_id'])) {
            $categories = $this->model_catalog_product->getProductCategories($this->request->get['product_id']);
        } else {
            $categories = array();
        }

        $this->data['product_categories'] = array();
        
        foreach ($categories as $category_id) {
            $category_info = $this->model_catalog_category->getCategory($category_id);
            
            if ($category_info) {
                $this->data['product_categories'][] = array(
                    'category_id' => $category_info['category_id'],
                    'name'        => $category_info['name']
                );
            }
        }

        $this->data['categories'] = $this->model_catalog_category->getCategories(0);

        // Load manufacturers
        $this->load->model('catalog/manufacturer');
        if (isset($this->request->post['manufacturer_id'])) {
            $this->data['manufacturer_id'] = $this->request->post['manufacturer_id'];
        } elseif (!empty($product_info)) {
            $this->data['manufacturer_id'] = $product_info['manufacturer_id'];
        } else {
            $this->data['manufacturer_id'] = 0;
        }

        if (isset($this->request->post['manufacturer'])) {
            $this->data['manufacturer'] = $this->request->post['manufacturer'];
        } elseif (!empty($product_info)) {
            $manufacturer_info = $this->model_catalog_manufacturer->getManufacturer($product_info['manufacturer_id']);
            if ($manufacturer_info) {
                $this->data['manufacturer'] = $manufacturer_info['name'];
            } else {
                $this->data['manufacturer'] = '';
            }
        } else {
            $this->data['manufacturer'] = '';
        }

        // Image
        $this->load->model('tool/image');
        
        if (isset($this->request->post['image'])) {
            $this->data['image'] = $this->request->post['image'];
        } elseif (!empty($product_info)) {
            $this->data['image'] = $product_info['image'];
        } else {
            $this->data['image'] = '';
        }

        if (isset($this->request->post['image']) && file_exists(DIR_IMAGE . $this->request->post['image'])) {
            $this->data['thumb'] = $this->model_tool_image->resize($this->request->post['image'], 100, 100);
        } elseif (!empty($product_info) && $product_info['image'] && file_exists(DIR_IMAGE . $product_info['image'])) {
            $this->data['thumb'] = $this->model_tool_image->resize($product_info['image'], 100, 100);
        } else {
            $this->data['thumb'] = $this->model_tool_image->resize('no_image.jpg', 100, 100);
        }

        $this->data['no_image'] = $this->model_tool_image->resize('no_image.jpg', 100, 100);

        $this->template = 'catalog/product_form.tpl';
        $this->children = array(
            'common/header',
            'common/footer'
        );

        $this->response->setOutput($this->render());
    }

    private function getCategoriesTreeWithPath() {
        $this->load->model('catalog/category');
        
        $categories = array();
        $categories[] = array('category_id' => '', 'name' => '-- Select --');
        
        // Get all categories with paths
        $query = $this->db->query("
            SELECT 
                c.category_id,
                c.parent_id,
                cd.name,
                GROUP_CONCAT(cd1.name ORDER BY cp.level SEPARATOR ' > ') AS path
            FROM " . DB_PREFIX . "category c
            LEFT JOIN " . DB_PREFIX . "category_path cp ON (c.category_id = cp.category_id)
            LEFT JOIN " . DB_PREFIX . "category c1 ON (cp.path_id = c1.category_id)
            LEFT JOIN " . DB_PREFIX . "category_description cd ON (c.category_id = cd.category_id)
            LEFT JOIN " . DB_PREFIX . "category_description cd1 ON (c1.category_id = cd1.category_id)
            WHERE cd.language_id = '" . (int)$this->config->get('config_language_id') . "'
            AND cd1.language_id = '" . (int)$this->config->get('config_language_id') . "'
            GROUP BY c.category_id
            ORDER BY path
        ");
        
        if ($query->rows) {
            foreach ($query->rows as $category) {
                $categories[] = array(
                    'category_id' => $category['category_id'],
                    'name'        => $category['path'] ? $category['path'] : $category['name']
                );
            }
        }
        
        return $categories;
    }

    private function getParentCategories($category_id) {
        $this->load->model('catalog/category');
        $parent_categories = array();
        
        // Get category info
        $category_info = $this->model_catalog_category->getCategory($category_id);
        
        if ($category_info && $category_info['parent_id'] > 0) {
            // Add parent category
            $parent_categories[] = $category_info['parent_id'];
            
            // Recursively get parent's parents
            $grand_parents = $this->getParentCategories($category_info['parent_id']);
            $parent_categories = array_merge($parent_categories, $grand_parents);
        }
        
        return $parent_categories;
    }

    protected function validateForm() {
        if (!$this->user->hasPermission('modify', 'catalog/product')) {
            $this->error['warning'] = $this->language->get('error_permission');
        }

        foreach ($this->request->post['product_description'] as $language_id => $value) {
            if ((utf8_strlen($value['name']) < 1) || (utf8_strlen($value['name']) > 255)) {
                $this->error['name'][$language_id] = $this->language->get('error_name');
            }
        }

        if ((utf8_strlen($this->request->post['model']) < 1) || (utf8_strlen($this->request->post['model']) > 64)) {
            $this->error['model'] = $this->language->get('error_model');
        }

        if ($this->error && !isset($this->error['warning'])) {
            $this->error['warning'] = $this->language->get('error_warning');
        }

        return !$this->error;
    }

    protected function validateDelete() {
        if (!$this->user->hasPermission('modify', 'catalog/product')) {
            $this->error['warning'] = $this->language->get('error_permission');
        }

        return !$this->error;
    }

    protected function validateCopy() {
        if (!$this->user->hasPermission('modify', 'catalog/product')) {
            $this->error['warning'] = $this->language->get('error_permission');
        }

        return !$this->error;
    }

    public function autocomplete() {
        $json = array();

        if (isset($this->request->get['filter_name']) || isset($this->request->get['filter_model'])) {
            $this->load->model('catalog/product');

            if (isset($this->request->get['filter_name'])) {
                $filter_name = $this->request->get['filter_name'];
            } else {
                $filter_name = '';
            }

            if (isset($this->request->get['filter_model'])) {
                $filter_model = $this->request->get['filter_model'];
            } else {
                $filter_model = '';
            }

            if (isset($this->request->get['limit'])) {
                $limit = $this->request->get['limit'];
            } else {
                $limit = 20;
            }

            $data = array(
                'filter_name'  => $filter_name,
                'filter_model' => $filter_model,
                'start'        => 0,
                'limit'        => $limit
            );

            $results = $this->model_catalog_product->getProducts($data);

            foreach ($results as $result) {
                $json[] = array(
                    'product_id' => $result['product_id'],
                    'name'       => strip_tags(html_entity_decode($result['name'], ENT_QUOTES, 'UTF-8')),
                    'model'      => $result['model'],
                    'price'      => $result['price']
                );
            }
        }

        $this->response->setOutput(json_encode($json));
    }
}
?>