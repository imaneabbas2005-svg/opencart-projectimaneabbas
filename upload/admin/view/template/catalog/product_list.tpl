<?php echo $header; ?>
<div id="content">
  <div class="breadcrumb">
    <?php foreach ($breadcrumbs as $breadcrumb) { ?>
    <?php echo $breadcrumb['separator']; ?><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a>
    <?php } ?>
  </div>
  <?php if ($error_warning) { ?>
  <div class="warning"><?php echo $error_warning; ?></div>
  <?php } ?>
  <?php if ($success) { ?>
  <div class="success"><?php echo $success; ?></div>
  <?php } ?>
  <div class="box">
    <div class="heading">
      <h1><img src="view/image/product.png" alt="" /> <?php echo $heading_title; ?></h1>
      <div class="buttons">
        <a href="<?php echo $insert; ?>" class="button"><?php echo $button_insert; ?></a>
        <a onclick="$('#form').submit();" class="button"><?php echo $button_delete; ?></a>
        <a onclick="copy();" class="button"><?php echo $button_copy; ?></a>
      </div>
    </div>
    <div class="content">
      <div class="filter-section" style="background: #F5F5F5; border: 1px solid #DDD; padding: 15px; margin-bottom: 20px; border-radius: 5px;">
        <table class="filter-table">
          <tr>
            <td>
              <table class="filter-row">
                <tr>
                  <td><strong><?php echo $column_name; ?>:</strong></td>
                  <td><input type="text" name="filter_name" value="<?php echo $filter_name; ?>" class="filter-input" /></td>
                  <td style="width: 20px;"></td>
                  <td><strong><?php echo $column_model; ?>:</strong></td>
                  <td><input type="text" name="filter_model" value="<?php echo $filter_model; ?>" class="filter-input" /></td>
                </tr>
                <tr>
                  <td><strong><?php echo $column_price; ?>:</strong></td>
                  <td><input type="text" name="filter_price" value="<?php echo $filter_price; ?>" class="filter-input" placeholder="e.g. 100" /></td>
                  <td style="width: 20px;"></td>
                  <td><strong><?php echo $column_cost; ?>:</strong></td>
                  <td>
                    <div style="position: relative;">
                      <select name="filter_cost_operator" class="filter-operator">
                        <option value="=">=</option>
                        <option value=">">&gt;</option>
                        <option value="<">&lt;</option>
                        <option value=">=">&gt;=</option>
                        <option value="<=">&lt;=</option>
                        <option value="!=">!=</option>
                      </select>
                      <input type="text" name="filter_cost" value="<?php echo $filter_cost; ?>" class="filter-input" style="padding-left: 45px;" placeholder="e.g. 50" />
                    </div>
                  </td>
                </tr>
                <tr>
                  <td><strong><?php echo $column_profit; ?>:</strong></td>
                  <td>
                    <div style="position: relative;">
                      <select name="filter_profit_operator" class="filter-operator">
                        <option value="=">=</option>
                        <option value=">">&gt;</option>
                        <option value="<">&lt;</option>
                        <option value=">=">&gt;=</option>
                        <option value="<=">&lt;=</option>
                        <option value="!=">!=</option>
                      </select>
                      <input type="text" name="filter_profit" value="<?php echo $filter_profit; ?>" class="filter-input" style="padding-left: 45px;" placeholder="e.g. 20" />
                    </div>
                  </td>
                  <td style="width: 20px;"></td>
                  <td><strong><?php echo $column_quantity; ?>:</strong></td>
                  <td><input type="text" name="filter_quantity" value="<?php echo $filter_quantity; ?>" class="filter-input" style="text-align: right;" /></td>
                </tr>
                <tr>
                  <td><strong><?php echo $column_category; ?>:</strong></td>
                  <td>
                    <select name="filter_category_path" class="filter-select" style="width: 250px;">
                      <option value="">-- <?php echo $text_select; ?> --</option>
                      <?php foreach ($categories as $category) { ?>
                      <option value="<?php echo $category['category_id']; ?>" <?php echo $category['category_id'] == $filter_category_path ? 'selected="selected"' : ''; ?>>
                        <?php echo $category['name']; ?>
                      </option>
                      <?php } ?>
                    </select>
                  </td>
                  <td style="width: 20px;"></td>
                  <td><strong><?php echo $column_status; ?>:</strong></td>
                  <td>
                    <select name="filter_status" class="filter-select">
                      <option value=""></option>
                      <option value="1" <?php echo $filter_status === '1' ? 'selected="selected"' : ''; ?>><?php echo $text_enabled; ?></option>
                      <option value="0" <?php echo $filter_status === '0' ? 'selected="selected"' : ''; ?>><?php echo $text_disabled; ?></option>
                    </select>
                  </td>
                </tr>
              </table>
            </td>
            <td style="vertical-align: bottom; padding-left: 20px;">
              <div class="filter-buttons">
                <a onclick="filter();" class="button" style="background: #5CB85C; border-color: #4CAE4C; color: white; padding: 8px 15px; font-weight: bold; cursor: pointer;">
                  <i class="fa fa-search" style="margin-right: 5px;"></i><?php echo $button_filter; ?>
                </a>
                <a href="index.php?route=catalog/product&token=<?php echo $token; ?>" class="button" style="background: #F0AD4E; border-color: #EEA236; color: white; padding: 8px 15px; font-weight: bold;">
                  <i class="fa fa-refresh" style="margin-right: 5px;"></i><?php echo $button_reset; ?>
                </a>
              </div>
            </td>
          </tr>
        </table>
      </div>
      
      <form action="<?php echo $delete; ?>" method="post" enctype="multipart/form-data" id="form">
        <table class="list">
          <thead>
            <tr>
              <td width="1" style="text-align: center;"><input type="checkbox" onclick="$('input[name*=\'selected\']').attr('checked', this.checked);" /></td>
              <td class="center"><?php echo $column_image; ?></td>
              <td class="left"><?php if ($sort == 'pd.name') { ?>
                <a href="<?php echo $sort_name; ?>" class="<?php echo strtolower($order); ?>"><?php echo $column_name; ?></a>
                <?php } else { ?>
                <a href="<?php echo $sort_name; ?>"><?php echo $column_name; ?></a>
                <?php } ?></td>
              <td class="left"><?php if ($sort == 'p.model') { ?>
                <a href="<?php echo $sort_model; ?>" class="<?php echo strtolower($order); ?>"><?php echo $column_model; ?></a>
                <?php } else { ?>
                <a href="<?php echo $sort_model; ?>"><?php echo $column_model; ?></a>
                <?php } ?></td>
              <td class="left"><?php if ($sort == 'p.price') { ?>
                <a href="<?php echo $sort_price; ?>" class="<?php echo strtolower($order); ?>"><?php echo $column_price; ?></a>
                <?php } else { ?>
                <a href="<?php echo $sort_price; ?>"><?php echo $column_price; ?></a>
                <?php } ?></td>
              <td class="left"><?php if ($sort == 'p.cost') { ?>
                <a href="<?php echo $sort_cost; ?>" class="<?php echo strtolower($order); ?>"><?php echo $column_cost; ?></a>
                <?php } else { ?>
                <a href="<?php echo $sort_cost; ?>"><?php echo $column_cost; ?></a>
                <?php } ?></td>
              <td class="left"><?php if ($sort == 'profit') { ?>
                <a href="<?php echo $sort_profit; ?>" class="<?php echo strtolower($order); ?>"><?php echo $column_profit; ?></a>
                <?php } else { ?>
                <a href="<?php echo $sort_profit; ?>"><?php echo $column_profit; ?></a>
                <?php } ?></td>
              <td class="left"><?php echo $column_category; ?></td>
              <td class="right"><?php if ($sort == 'p.quantity') { ?>
                <a href="<?php echo $sort_quantity; ?>" class="<?php echo strtolower($order); ?>"><?php echo $column_quantity; ?></a>
                <?php } else { ?>
                <a href="<?php echo $sort_quantity; ?>"><?php echo $column_quantity; ?></a>
                <?php } ?></td>
              <td class="left"><?php if ($sort == 'p.status') { ?>
                <a href="<?php echo $sort_status; ?>" class="<?php echo strtolower($order); ?>"><?php echo $column_status; ?></a>
                <?php } else { ?>
                <a href="<?php echo $sort_status; ?>"><?php echo $column_status; ?></a>
                <?php } ?></td>
              <td class="right"><?php echo $column_action; ?></td>
            </tr>
          </thead>
          <tbody>
            <?php if ($products) { ?>
            <?php foreach ($products as $product) { ?>
            <tr>
              <td style="text-align: center;"><?php if ($product['selected']) { ?>
                <input type="checkbox" name="selected[]" value="<?php echo $product['product_id']; ?>" checked="checked" />
                <?php } else { ?>
                <input type="checkbox" name="selected[]" value="<?php echo $product['product_id']; ?>" />
                <?php } ?></td>
              <td class="center"><img src="<?php echo $product['image']; ?>" alt="<?php echo $product['name']; ?>" style="padding: 1px; border: 1px solid #DDDDDD;" /></td>
              <td class="left"><?php echo $product['name']; ?></td>
              <td class="left"><?php echo $product['model']; ?></td>
              <td class="left"><?php echo $product['price']; ?></td>
              <td class="left"><?php echo $product['cost']; ?></td>
              <td class="left"><?php echo $product['profit']; ?></td>
              <td class="left">
                <div class="category-paths">
                  <?php 
                  // Fixed: Don't use empty() on function return
                  $categories_array = explode('<br>', $product['categories']);
                  foreach ($categories_array as $category) {
                    $trimmed_category = trim($category);
                    if ($trimmed_category != '') {
                      echo '<div class="category-path">' . $category . '</div>';
                    }
                  }
                  ?>
                </div>
              </td>
              <td class="right"><?php echo $product['quantity']; ?></td>
              <td class="left"><?php echo $product['status']; ?></td>
              <td class="right">
                <div class="action-buttons">
                  <?php foreach ($product['action'] as $action) { ?>
                  <a href="<?php echo $action['href']; ?>" class="button button-small" style="background: #337AB7; color: white; padding: 3px 8px; margin: 2px; border-radius: 3px;"><?php echo $action['text']; ?></a>
                  <?php } ?>
                </div>
              </td>
            </tr>
            <?php } ?>
            <?php } else { ?>
            <tr>
              <td class="center" colspan="11"><?php echo $text_no_results; ?></td>
            </tr>
            <?php } ?>
          </tbody>
        </table>
      </form>
      <div class="pagination"><?php echo $pagination; ?></div>
    </div>
  </div>
</div>

<style>
.filter-section {
    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
}
.filter-table {
    width: 100%;
}
.filter-row td {
    padding: 5px 10px;
}
.filter-input {
    padding: 6px 10px;
    border: 1px solid #CCC;
    border-radius: 3px;
    width: 150px;
}
.filter-select {
    padding: 6px 10px;
    border: 1px solid #CCC;
    border-radius: 3px;
    width: 170px;
}
.filter-operator {
    position: absolute;
    left: 0;
    top: 0;
    width: 40px;
    height: 100%;
    border: 1px solid #CCC;
    border-right: none;
    border-radius: 3px 0 0 3px;
    background: #EEE;
}
.filter-buttons {
    display: flex;
    flex-direction: column;
    gap: 10px;
}
.button-small {
    font-size: 12px;
    padding: 2px 6px;
}
.action-buttons {
    display: flex;
    gap: 5px;
}
.category-paths {
    max-width: 250px;
}
.category-path {
    padding: 2px 0;
    border-bottom: 1px dashed #EEE;
}
.category-path:last-child {
    border-bottom: none;
}
.sort-asc:after {
    content: " ↑";
    font-weight: bold;
}
.sort-desc:after {
    content: " ↓";
    font-weight: bold;
}
</style>

<script type="text/javascript"><!--
function filter() {
    var url = 'index.php?route=catalog/product&token=<?php echo $token; ?>';
    
    var filter_name = $('input[name="filter_name"]').val();
    if (filter_name) {
        url += '&filter_name=' + encodeURIComponent(filter_name);
    }
    
    var filter_model = $('input[name="filter_model"]').val();
    if (filter_model) {
        url += '&filter_model=' + encodeURIComponent(filter_model);
    }
    
    var filter_price = $('input[name="filter_price"]').val();
    if (filter_price) {
        url += '&filter_price=' + encodeURIComponent(filter_price);
    }
    
    var filter_cost = $('input[name="filter_cost"]').val();
    var filter_cost_operator = $('select[name="filter_cost_operator"]').val();
    if (filter_cost) {
        // Handle the operator properly
        url += '&filter_cost=' + encodeURIComponent(filter_cost_operator + filter_cost);
    }
    
    var filter_profit = $('input[name="filter_profit"]').val();
    var filter_profit_operator = $('select[name="filter_profit_operator"]').val();
    if (filter_profit) {
        url += '&filter_profit=' + encodeURIComponent(filter_profit_operator + filter_profit);
    }
    
    var filter_category_path = $('select[name="filter_category_path"]').val();
    if (filter_category_path) {
        url += '&filter_category_path=' + encodeURIComponent(filter_category_path);
    }
    
    var filter_quantity = $('input[name="filter_quantity"]').val();
    if (filter_quantity) {
        url += '&filter_quantity=' + encodeURIComponent(filter_quantity);
    }
    
    var filter_status = $('select[name="filter_status"]').val();
    if (filter_status !== '') {
        url += '&filter_status=' + encodeURIComponent(filter_status);
    }
    
    var sort = '<?php echo $sort; ?>';
    var order = '<?php echo $order; ?>';
    
    if (sort) {
        url += '&sort=' + sort;
    }
    
    if (order) {
        url += '&order=' + order;
    }
    
    window.location.href = url;
}

function copy() {
    var selected = $('input[name^="selected"][type="checkbox"]:checked').length;
    if (selected == 0) {
        alert('<?php echo $text_select; ?>');
        return false;
    } else {
        $('#form').attr('action', 'index.php?route=catalog/product/copy&token=<?php echo $token; ?>');
        $('#form').submit();
    }
}

// Set operator values based on current filter values
$(document).ready(function() {
    // Handle cost filter
    var filterCost = '<?php echo $filter_cost; ?>';
    if (filterCost) {
        var match = filterCost.match(/^([><!]?=?)?(.+)$/);
        if (match) {
            var operator = match[1] || '=';
            var value = match[2];
            $('select[name="filter_cost_operator"]').val(operator);
            $('input[name="filter_cost"]').val(value);
        }
    }
    
    // Handle profit filter
    var filterProfit = '<?php echo $filter_profit; ?>';
    if (filterProfit) {
        var match = filterProfit.match(/^([><!]?=?)?(.+)$/);
        if (match) {
            var operator = match[1] || '=';
            var value = match[2];
            $('select[name="filter_profit_operator"]').val(operator);
            $('input[name="filter_profit"]').val(value);
        }
    }
    
    // Enter key support
    $('.filter-input, .filter-select').keydown(function(e) {
        if (e.keyCode == 13) {
            filter();
        }
    });
});
//--></script>
<?php echo $footer; ?>