<?php
class ModelCatalogProduct extends Model {
    public function addProduct($data) {
        // Set default values for missing fields
        $data['sku'] = isset($data['sku']) ? $data['sku'] : '';
        $data['upc'] = isset($data['upc']) ? $data['upc'] : '';
        $data['ean'] = isset($data['ean']) ? $data['ean'] : '';
        $data['jan'] = isset($data['jan']) ? $data['jan'] : '';
        $data['isbn'] = isset($data['isbn']) ? $data['isbn'] : '';
        $data['mpn'] = isset($data['mpn']) ? $data['mpn'] : '';
        $data['location'] = isset($data['location']) ? $data['location'] : '';
        $data['minimum'] = isset($data['minimum']) ? (int)$data['minimum'] : 1;
        $data['subtract'] = isset($data['subtract']) ? (int)$data['subtract'] : 1;
        $data['stock_status_id'] = isset($data['stock_status_id']) ? (int)$data['stock_status_id'] : 5;
        $data['date_available'] = isset($data['date_available']) ? $data['date_available'] : date('Y-m-d');
        $data['manufacturer_id'] = isset($data['manufacturer_id']) ? (int)$data['manufacturer_id'] : 0;
        $data['shipping'] = isset($data['shipping']) ? (int)$data['shipping'] : 1;
        $data['price'] = isset($data['price']) ? (float)$data['price'] : 0;
        $data['cost'] = isset($data['cost']) ? (float)$data['cost'] : 0;
        $data['points'] = isset($data['points']) ? (int)$data['points'] : 0;
        $data['weight'] = isset($data['weight']) ? (float)$data['weight'] : 0;
        $data['weight_class_id'] = isset($data['weight_class_id']) ? (int)$data['weight_class_id'] : 1;
        $data['length'] = isset($data['length']) ? (float)$data['length'] : 0;
        $data['width'] = isset($data['width']) ? (float)$data['width'] : 0;
        $data['height'] = isset($data['height']) ? (float)$data['height'] : 0;
        $data['length_class_id'] = isset($data['length_class_id']) ? (int)$data['length_class_id'] : 1;
        $data['status'] = isset($data['status']) ? (int)$data['status'] : 1;
        $data['tax_class_id'] = isset($data['tax_class_id']) ? (int)$data['tax_class_id'] : 0;
        $data['sort_order'] = isset($data['sort_order']) ? (int)$data['sort_order'] : 0;
        $data['keyword'] = isset($data['keyword']) ? $data['keyword'] : '';
        
        $this->db->query("INSERT INTO " . DB_PREFIX . "product SET 
            model = '" . $this->db->escape($data['model']) . "',
            sku = '" . $this->db->escape($data['sku']) . "',
            upc = '" . $this->db->escape($data['upc']) . "',
            ean = '" . $this->db->escape($data['ean']) . "',
            jan = '" . $this->db->escape($data['jan']) . "',
            isbn = '" . $this->db->escape($data['isbn']) . "',
            mpn = '" . $this->db->escape($data['mpn']) . "',
            location = '" . $this->db->escape($data['location']) . "',
            quantity = '" . (int)$data['quantity'] . "',
            minimum = '" . (int)$data['minimum'] . "',
            subtract = '" . (int)$data['subtract'] . "',
            stock_status_id = '" . (int)$data['stock_status_id'] . "',
            date_available = '" . $this->db->escape($data['date_available']) . "',
            manufacturer_id = '" . (int)$data['manufacturer_id'] . "',
            shipping = '" . (int)$data['shipping'] . "',
            price = '" . (float)$data['price'] . "',
            cost = '" . (float)$data['cost'] . "',
            points = '" . (int)$data['points'] . "',
            weight = '" . (float)$data['weight'] . "',
            weight_class_id = '" . (int)$data['weight_class_id'] . "',
            length = '" . (float)$data['length'] . "',
            width = '" . (float)$data['width'] . "',
            height = '" . (float)$data['height'] . "',
            length_class_id = '" . (int)$data['length_class_id'] . "',
            status = '" . (int)$data['status'] . "',
            tax_class_id = '" . (int)$data['tax_class_id'] . "',
            sort_order = '" . (int)$data['sort_order'] . "',
            date_added = NOW(),
            date_modified = NOW()");
        
        $product_id = $this->db->getLastId();
        
        if (isset($data['image'])) {
            $this->db->query("UPDATE " . DB_PREFIX . "product SET image = '" . $this->db->escape($data['image']) . "' WHERE product_id = '" . (int)$product_id . "'");
        }
        
        foreach ($data['product_description'] as $language_id => $value) {
            $tag = isset($value['tag']) ? $value['tag'] : '';
            $this->db->query("INSERT INTO " . DB_PREFIX . "product_description SET 
                product_id = '" . (int)$product_id . "',
                language_id = '" . (int)$language_id . "',
                name = '" . $this->db->escape($value['name']) . "',
                description = '" . $this->db->escape($value['description']) . "',
                meta_description = '" . $this->db->escape($value['meta_description']) . "',
                meta_keyword = '" . $this->db->escape($value['meta_keyword']) . "',
                tag = '" . $this->db->escape($tag) . "'");
        }
        
        if (isset($data['product_category'])) {
            foreach ($data['product_category'] as $category_id) {
                $this->db->query("INSERT INTO " . DB_PREFIX . "product_to_category SET 
                    product_id = '" . (int)$product_id . "',
                    category_id = '" . (int)$category_id . "'");
            }
        }
        
        // Default to store 0
        $this->db->query("INSERT INTO " . DB_PREFIX . "product_to_store SET 
            product_id = '" . (int)$product_id . "',
            store_id = '0'");
        
        if ($data['keyword']) {
            $this->db->query("INSERT INTO " . DB_PREFIX . "url_alias SET 
                query = 'product_id=" . (int)$product_id . "',
                keyword = '" . $this->db->escape($data['keyword']) . "'");
        }
        
        $this->cache->delete('product');
        
        return $product_id;
    }
    
    public function editProduct($product_id, $data) {
        // Set default values for missing fields
        $data['sku'] = isset($data['sku']) ? $data['sku'] : '';
        $data['upc'] = isset($data['upc']) ? $data['upc'] : '';
        $data['ean'] = isset($data['ean']) ? $data['ean'] : '';
        $data['jan'] = isset($data['jan']) ? $data['jan'] : '';
        $data['isbn'] = isset($data['isbn']) ? $data['isbn'] : '';
        $data['mpn'] = isset($data['mpn']) ? $data['mpn'] : '';
        $data['location'] = isset($data['location']) ? $data['location'] : '';
        $data['minimum'] = isset($data['minimum']) ? (int)$data['minimum'] : 1;
        $data['subtract'] = isset($data['subtract']) ? (int)$data['subtract'] : 1;
        $data['stock_status_id'] = isset($data['stock_status_id']) ? (int)$data['stock_status_id'] : 5;
        $data['date_available'] = isset($data['date_available']) ? $data['date_available'] : date('Y-m-d');
        $data['manufacturer_id'] = isset($data['manufacturer_id']) ? (int)$data['manufacturer_id'] : 0;
        $data['shipping'] = isset($data['shipping']) ? (int)$data['shipping'] : 1;
        $data['price'] = isset($data['price']) ? (float)$data['price'] : 0;
        $data['cost'] = isset($data['cost']) ? (float)$data['cost'] : 0;
        $data['points'] = isset($data['points']) ? (int)$data['points'] : 0;
        $data['weight'] = isset($data['weight']) ? (float)$data['weight'] : 0;
        $data['weight_class_id'] = isset($data['weight_class_id']) ? (int)$data['weight_class_id'] : 1;
        $data['length'] = isset($data['length']) ? (float)$data['length'] : 0;
        $data['width'] = isset($data['width']) ? (float)$data['width'] : 0;
        $data['height'] = isset($data['height']) ? (float)$data['height'] : 0;
        $data['length_class_id'] = isset($data['length_class_id']) ? (int)$data['length_class_id'] : 1;
        $data['status'] = isset($data['status']) ? (int)$data['status'] : 1;
        $data['tax_class_id'] = isset($data['tax_class_id']) ? (int)$data['tax_class_id'] : 0;
        $data['sort_order'] = isset($data['sort_order']) ? (int)$data['sort_order'] : 0;
        $data['keyword'] = isset($data['keyword']) ? $data['keyword'] : '';
        
        $this->db->query("UPDATE " . DB_PREFIX . "product SET 
            model = '" . $this->db->escape($data['model']) . "',
            sku = '" . $this->db->escape($data['sku']) . "',
            upc = '" . $this->db->escape($data['upc']) . "',
            ean = '" . $this->db->escape($data['ean']) . "',
            jan = '" . $this->db->escape($data['jan']) . "',
            isbn = '" . $this->db->escape($data['isbn']) . "',
            mpn = '" . $this->db->escape($data['mpn']) . "',
            location = '" . $this->db->escape($data['location']) . "',
            quantity = '" . (int)$data['quantity'] . "',
            minimum = '" . (int)$data['minimum'] . "',
            subtract = '" . (int)$data['subtract'] . "',
            stock_status_id = '" . (int)$data['stock_status_id'] . "',
            date_available = '" . $this->db->escape($data['date_available']) . "',
            manufacturer_id = '" . (int)$data['manufacturer_id'] . "',
            shipping = '" . (int)$data['shipping'] . "',
            price = '" . (float)$data['price'] . "',
            cost = '" . (float)$data['cost'] . "',
            points = '" . (int)$data['points'] . "',
            weight = '" . (float)$data['weight'] . "',
            weight_class_id = '" . (int)$data['weight_class_id'] . "',
            length = '" . (float)$data['length'] . "',
            width = '" . (float)$data['width'] . "',
            height = '" . (float)$data['height'] . "',
            length_class_id = '" . (int)$data['length_class_id'] . "',
            status = '" . (int)$data['status'] . "',
            tax_class_id = '" . (int)$data['tax_class_id'] . "',
            sort_order = '" . (int)$data['sort_order'] . "',
            date_modified = NOW()
            WHERE product_id = '" . (int)$product_id . "'");
        
        if (isset($data['image'])) {
            $this->db->query("UPDATE " . DB_PREFIX . "product SET image = '" . $this->db->escape($data['image']) . "' WHERE product_id = '" . (int)$product_id . "'");
        }
        
        $this->db->query("DELETE FROM " . DB_PREFIX . "product_description WHERE product_id = '" . (int)$product_id . "'");
        
        foreach ($data['product_description'] as $language_id => $value) {
            $tag = isset($value['tag']) ? $value['tag'] : '';
            $this->db->query("INSERT INTO " . DB_PREFIX . "product_description SET 
                product_id = '" . (int)$product_id . "',
                language_id = '" . (int)$language_id . "',
                name = '" . $this->db->escape($value['name']) . "',
                description = '" . $this->db->escape($value['description']) . "',
                meta_description = '" . $this->db->escape($value['meta_description']) . "',
                meta_keyword = '" . $this->db->escape($value['meta_keyword']) . "',
                tag = '" . $this->db->escape($tag) . "'");
        }
        
        $this->db->query("DELETE FROM " . DB_PREFIX . "product_to_category WHERE product_id = '" . (int)$product_id . "'");
        
        if (isset($data['product_category'])) {
            foreach ($data['product_category'] as $category_id) {
                $this->db->query("INSERT INTO " . DB_PREFIX . "product_to_category SET 
                    product_id = '" . (int)$product_id . "',
                    category_id = '" . (int)$category_id . "'");
            }
        }
        
        $this->db->query("DELETE FROM " . DB_PREFIX . "url_alias WHERE query = 'product_id=" . (int)$product_id . "'");
        
        if ($data['keyword']) {
            $this->db->query("INSERT INTO " . DB_PREFIX . "url_alias SET 
                query = 'product_id=" . (int)$product_id . "',
                keyword = '" . $this->db->escape($data['keyword']) . "'");
        }
        
        $this->cache->delete('product');
    }
    
    public function deleteProduct($product_id) {
        $this->db->query("DELETE FROM " . DB_PREFIX . "product WHERE product_id = '" . (int)$product_id . "'");
        $this->db->query("DELETE FROM " . DB_PREFIX . "product_description WHERE product_id = '" . (int)$product_id . "'");
        $this->db->query("DELETE FROM " . DB_PREFIX . "product_to_category WHERE product_id = '" . (int)$product_id . "'");
        $this->db->query("DELETE FROM " . DB_PREFIX . "product_to_store WHERE product_id = '" . (int)$product_id . "'");
        $this->db->query("DELETE FROM " . DB_PREFIX . "product_image WHERE product_id = '" . (int)$product_id . "'");
        $this->db->query("DELETE FROM " . DB_PREFIX . "product_option WHERE product_id = '" . (int)$product_id . "'");
        $this->db->query("DELETE FROM " . DB_PREFIX . "product_option_value WHERE product_id = '" . (int)$product_id . "'");
        $this->db->query("DELETE FROM " . DB_PREFIX . "product_related WHERE product_id = '" . (int)$product_id . "'");
        $this->db->query("DELETE FROM " . DB_PREFIX . "product_related WHERE related_id = '" . (int)$product_id . "'");
        $this->db->query("DELETE FROM " . DB_PREFIX . "review WHERE product_id = '" . (int)$product_id . "'");
        $this->db->query("DELETE FROM " . DB_PREFIX . "url_alias WHERE query = 'product_id=" . (int)$product_id . "'");
        
        $this->cache->delete('product');
    }
    
    public function copyProduct($product_id) {
        $query = $this->db->query("SELECT DISTINCT * FROM " . DB_PREFIX . "product p 
            LEFT JOIN " . DB_PREFIX . "product_description pd ON (p.product_id = pd.product_id) 
            WHERE p.product_id = '" . (int)$product_id . "' 
            AND pd.language_id = '" . (int)$this->config->get('config_language_id') . "'");
        
        if ($query->num_rows) {
            $data = $query->row;
            $data['keyword'] = '';
            
            $data = array_merge($data, array('product_description' => $this->getProductDescriptions($product_id)));
            $data = array_merge($data, array('product_category' => $this->getProductCategories($product_id)));
            
            $this->addProduct($data);
        }
    }
    
    public function getProduct($product_id) {
        $query = $this->db->query("SELECT DISTINCT * FROM " . DB_PREFIX . "product p 
            LEFT JOIN " . DB_PREFIX . "product_description pd ON (p.product_id = pd.product_id) 
            WHERE p.product_id = '" . (int)$product_id . "' 
            AND pd.language_id = '" . (int)$this->config->get('config_language_id') . "'");
        
        return $query->row;
    }
    
    public function getProducts($data = array()) {
        $sql = "SELECT p.*, pd.name, p.price, p.cost, (p.price - p.cost) as profit, p.quantity, p.status, p.model FROM " . DB_PREFIX . "product p 
            LEFT JOIN " . DB_PREFIX . "product_description pd ON (p.product_id = pd.product_id) 
            WHERE pd.language_id = '" . (int)$this->config->get('config_language_id') . "'";
        
        if (!empty($data['filter_name'])) {
            $sql .= " AND pd.name LIKE '" . $this->db->escape($data['filter_name']) . "%'";
        }
        
        if (!empty($data['filter_model'])) {
            $sql .= " AND p.model LIKE '" . $this->db->escape($data['filter_model']) . "%'";
        }
        
        if (!empty($data['filter_price'])) {
            $sql .= " AND p.price = '" . (float)$data['filter_price'] . "'";
        }
        
        if (!empty($data['filter_cost'])) {
            $cost_filter = $data['filter_cost'];
            // Handle inequality operators
            if (preg_match('/^([><!]?=?)(.+)$/', $cost_filter, $matches)) {
                $operator = $matches[1] ?: '=';
                $value = (float)$matches[2];
                $sql .= " AND p.cost " . $operator . " '" . $value . "'";
            } else {
                $sql .= " AND p.cost = '" . (float)$cost_filter . "'";
            }
        }
        
        if (!empty($data['filter_profit'])) {
            $profit_filter = $data['filter_profit'];
            // Handle inequality operators for profit
            if (preg_match('/^([><!]?=?)(.+)$/', $profit_filter, $matches)) {
                $operator = $matches[1] ?: '=';
                $value = (float)$matches[2];
                $sql .= " AND (p.price - p.cost) " . $operator . " '" . $value . "'";
            } else {
                $profit = (float)$data['filter_profit'];
                $sql .= " AND (p.price - p.cost) = '" . $profit . "'";
            }
        }
        
        if (!empty($data['filter_quantity'])) {
            $sql .= " AND p.quantity = '" . (int)$data['filter_quantity'] . "'";
        }
        
        if (isset($data['filter_status']) && !is_null($data['filter_status'])) {
            $sql .= " AND p.status = '" . (int)$data['filter_status'] . "'";
        }
        
        if (!empty($data['filter_category_path'])) {
            $sql .= " AND p.product_id IN (SELECT product_id FROM " . DB_PREFIX . "product_to_category WHERE category_id = '" . (int)$data['filter_category_path'] . "')";
        }
        
        $sort_data = array(
            'pd.name',
            'p.model',
            'p.price',
            'p.cost',
            'profit',
            'p.quantity',
            'p.status'
        );
        
        if (isset($data['sort']) && in_array($data['sort'], $sort_data)) {
            $sql .= " ORDER BY " . $data['sort'];
        } else {
            $sql .= " ORDER BY pd.name";
        }
        
        if (isset($data['order']) && ($data['order'] == 'DESC')) {
            $sql .= " DESC";
        } else {
            $sql .= " ASC";
        }
        
        if (isset($data['start']) || isset($data['limit'])) {
            if ($data['start'] < 0) {
                $data['start'] = 0;
            }
            
            if ($data['limit'] < 1) {
                $data['limit'] = 20;
            }
            
            $sql .= " LIMIT " . (int)$data['start'] . "," . (int)$data['limit'];
        }
        
        $query = $this->db->query($sql);
        
        return $query->rows;
    }
    
    public function getProductDescriptions($product_id) {
        $product_description_data = array();
        
        $query = $this->db->query("SELECT * FROM " . DB_PREFIX . "product_description WHERE product_id = '" . (int)$product_id . "'");
        
        foreach ($query->rows as $result) {
            $product_description_data[$result['language_id']] = array(
                'name'             => $result['name'],
                'description'      => $result['description'],
                'meta_description' => $result['meta_description'],
                'meta_keyword'     => $result['meta_keyword'],
                'tag'              => isset($result['tag']) ? $result['tag'] : ''
            );
        }
        
        return $product_description_data;
    }
    
    public function getProductCategories($product_id) {
        $product_category_data = array();
        
        $query = $this->db->query("SELECT * FROM " . DB_PREFIX . "product_to_category WHERE product_id = '" . (int)$product_id . "'");
        
        foreach ($query->rows as $result) {
            $product_category_data[] = $result['category_id'];
        }
        
        return $product_category_data;
    }
    
    public function getProductStores($product_id) {
        $product_store_data = array();
        
        $query = $this->db->query("SELECT * FROM " . DB_PREFIX . "product_to_store WHERE product_id = '" . (int)$product_id . "'");
        
        foreach ($query->rows as $result) {
            $product_store_data[] = $result['store_id'];
        }
        
        return $product_store_data;
    }
    
    public function getTotalProducts($data = array()) {
        $sql = "SELECT COUNT(DISTINCT p.product_id) AS total FROM " . DB_PREFIX . "product p 
            LEFT JOIN " . DB_PREFIX . "product_description pd ON (p.product_id = pd.product_id) 
            WHERE pd.language_id = '" . (int)$this->config->get('config_language_id') . "'";
        
        if (!empty($data['filter_name'])) {
            $sql .= " AND pd.name LIKE '" . $this->db->escape($data['filter_name']) . "%'";
        }
        
        if (!empty($data['filter_model'])) {
            $sql .= " AND p.model LIKE '" . $this->db->escape($data['filter_model']) . "%'";
        }
        
        if (!empty($data['filter_price'])) {
            $sql .= " AND p.price = '" . (float)$data['filter_price'] . "'";
        }
        
        if (!empty($data['filter_cost'])) {
            $cost_filter = $data['filter_cost'];
            // Handle inequality operators
            if (preg_match('/^([><!]?=?)(.+)$/', $cost_filter, $matches)) {
                $operator = $matches[1] ?: '=';
                $value = (float)$matches[2];
                $sql .= " AND p.cost " . $operator . " '" . $value . "'";
            } else {
                $sql .= " AND p.cost = '" . (float)$cost_filter . "'";
            }
        }
        
        if (!empty($data['filter_profit'])) {
            $profit_filter = $data['filter_profit'];
            // Handle inequality operators for profit
            if (preg_match('/^([><!]?=?)(.+)$/', $profit_filter, $matches)) {
                $operator = $matches[1] ?: '=';
                $value = (float)$matches[2];
                $sql .= " AND (p.price - p.cost) " . $operator . " '" . $value . "'";
            } else {
                $profit = (float)$data['filter_profit'];
                $sql .= " AND (p.price - p.cost) = '" . $profit . "'";
            }
        }
        
        if (!empty($data['filter_quantity'])) {
            $sql .= " AND p.quantity = '" . (int)$data['filter_quantity'] . "'";
        }
        
        if (isset($data['filter_status']) && !is_null($data['filter_status'])) {
            $sql .= " AND p.status = '" . (int)$data['filter_status'] . "'";
        }
        
        if (!empty($data['filter_category_path'])) {
            $sql .= " AND p.product_id IN (SELECT product_id FROM " . DB_PREFIX . "product_to_category WHERE category_id = '" . (int)$data['filter_category_path'] . "')";
        }
        
        $query = $this->db->query($sql);
        
        return $query->row['total'];
    }
    
    public function getProductCategoryPaths($product_id) {
        $query = $this->db->query("
            SELECT 
                c.category_id,
                GROUP_CONCAT(cd1.name ORDER BY cp.level SEPARATOR ' > ') AS path
            FROM " . DB_PREFIX . "product_to_category p2c
            LEFT JOIN " . DB_PREFIX . "category_path cp ON (p2c.category_id = cp.category_id)
            LEFT JOIN " . DB_PREFIX . "category c ON (cp.path_id = c.category_id)
            LEFT JOIN " . DB_PREFIX . "category_description cd1 ON (c.category_id = cd1.category_id)
            WHERE p2c.product_id = '" . (int)$product_id . "'
            AND cd1.language_id = '" . (int)$this->config->get('config_language_id') . "'
            GROUP BY c.category_id
            ORDER BY path
        ");
        
        return $query->rows;
    }
}
?>