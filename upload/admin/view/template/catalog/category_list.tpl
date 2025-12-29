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
      <h1><img src="view/image/category.png" alt="" /> <?php echo $heading_title; ?></h1>
      <div class="buttons">
        <a href="<?php echo $repair; ?>" class="button"><?php echo $button_repair; ?></a>
        <a href="<?php echo $insert; ?>" class="button"><?php echo $button_insert; ?></a>
        <a onclick="$('#form').submit();" class="button"><?php echo $button_delete; ?></a>
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
                  <td><strong><?php echo $column_product_count; ?>:</strong></td>
                  <td>
                    <div style="position: relative;">
                      <select name="filter_product_count_operator" class="filter-operator">
                        <option value="=">=</option>
                        <option value=">">&gt;</option>
                        <option value="<">&lt;</option>
                        <option value=">=">&gt;=</option>
                        <option value="<=">&lt;=</option>
                        <option value="!=">!=</option>
                      </select>
                      <input type="text" name="filter_product_count" value="<?php echo $filter_product_count; ?>" class="filter-input" style="padding-left: 45px;" placeholder="e.g. 10" />
                    </div>
                  </td>
                </tr>
                <tr>
                  <td><strong><?php echo $column_parent_path; ?>:</strong></td>
                  <td>
                    <select name="filter_parent_path" class="filter-select" style="width: 250px;">
                      <option value="">-- <?php echo $text_select; ?> --</option>
                      <?php foreach ($all_categories as $category) { ?>
                      <option value="<?php echo $category['category_id']; ?>" <?php echo $category['category_id'] == $filter_parent_path ? 'selected="selected"' : ''; ?>>
                        <?php echo $category['full_path']; ?>
                      </option>
                      <?php } ?>
                    </select>
                  </td>
                  <td style="width: 20px;"></td>
                  <td></td>
                  <td>
                    <div class="filter-buttons" style="display: flex; gap: 10px;">
                      <a onclick="filter();" class="button" style="background: #5CB85C; border-color: #4CAE4C; color: white; padding: 8px 15px; font-weight: bold; cursor: pointer;">
                        <i class="fa fa-search" style="margin-right: 5px;"></i><?php echo $button_filter; ?>
                      </a>
                      <a href="index.php?route=catalog/category&token=<?php echo $token; ?>" class="button" style="background: #F0AD4E; border-color: #EEA236; color: white; padding: 8px 15px; font-weight: bold;">
                        <i class="fa fa-refresh" style="margin-right: 5px;"></i><?php echo $button_reset; ?>
                      </a>
                    </div>
                  </td>
                </tr>
              </table>
            </td>
          </tr>
        </table>
      </div>
      
      <form action="<?php echo $delete; ?>" method="post" enctype="multipart/form-data" id="form">
        <table class="list">
          <thead>
            <tr>
              <td width="1" style="text-align: center;"><input type="checkbox" onclick="$('input[name*=\'selected\']').attr('checked', this.checked);" /></td>
              <td class="left"><?php if ($sort == 'name') { ?>
                <a href="<?php echo $sort_name; ?>" class="<?php echo strtolower($order); ?>"><?php echo $column_name; ?></a>
                <?php } else { ?>
                <a href="<?php echo $sort_name; ?>"><?php echo $column_name; ?></a>
                <?php } ?></td>
              <td class="right"><?php if ($sort == 'product_count') { ?>
                <a href="<?php echo $sort_product_count; ?>" class="<?php echo strtolower($order); ?>"><?php echo $column_product_count; ?></a>
                <?php } else { ?>
                <a href="<?php echo $sort_product_count; ?>"><?php echo $column_product_count; ?></a>
                <?php } ?></td>
              <td class="left"><?php echo $column_parent_path; ?></td>
              <td class="right"><?php if ($sort == 'sort_order') { ?>
                <a href="<?php echo $sort_sort_order; ?>" class="<?php echo strtolower($order); ?>"><?php echo $column_sort_order; ?></a>
                <?php } else { ?>
                <a href="<?php echo $sort_sort_order; ?>"><?php echo $column_sort_order; ?></a>
                <?php } ?></td>
              <td class="right"><?php echo $column_action; ?></td>
            </tr>
          </thead>
          <tbody>
            <?php if ($categories) { ?>
            <?php foreach ($categories as $category) { ?>
            <tr>
              <td style="text-align: center;"><?php if ($category['selected']) { ?>
                <input type="checkbox" name="selected[]" value="<?php echo $category['category_id']; ?>" checked="checked" />
                <?php } else { ?>
                <input type="checkbox" name="selected[]" value="<?php echo $category['category_id']; ?>" />
                <?php } ?></td>
              <td class="left"><?php echo $category['name']; ?></td>
              <td class="right"><?php echo $category['product_count']; ?></td>
              <td class="left"><?php echo $category['parent_path']; ?></td>
              <td class="right"><?php echo $category['sort_order']; ?></td>
              <td class="right">
                <div class="action-buttons">
                  <?php foreach ($category['action'] as $action) { ?>
                  <a href="<?php echo $action['href']; ?>" class="button button-small" style="background: #337AB7; color: white; padding: 3px 8px; margin: 2px; border-radius: 3px;"><?php echo $action['text']; ?></a>
                  <?php } ?>
                </div>
              </td>
            </tr>
            <?php } ?>
            <?php } else { ?>
            <tr>
              <td class="center" colspan="6"><?php echo $text_no_results; ?></td>
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
.button-small {
    font-size: 12px;
    padding: 2px 6px;
}
.action-buttons {
    display: flex;
    gap: 5px;
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
    var url = 'index.php?route=catalog/category&token=<?php echo $token; ?>';
    
    var filter_name = $('input[name="filter_name"]').val();
    if (filter_name) {
        url += '&filter_name=' + encodeURIComponent(filter_name);
    }
    
    var filter_product_count = $('input[name="filter_product_count"]').val();
    var filter_product_count_operator = $('select[name="filter_product_count_operator"]').val();
    if (filter_product_count) {
        url += '&filter_product_count=' + encodeURIComponent(filter_product_count_operator + filter_product_count);
    }
    
    var filter_parent_path = $('select[name="filter_parent_path"]').val();
    if (filter_parent_path) {
        url += '&filter_parent_path=' + encodeURIComponent(filter_parent_path);
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

$(document).ready(function() {
    var filterProductCount = '<?php echo $filter_product_count; ?>';
    if (filterProductCount) {
        var match = filterProductCount.match(/^([><!]?=?)?(.+)$/);
        if (match) {
            var operator = match[1] || '=';
            var value = match[2];
            $('select[name="filter_product_count_operator"]').val(operator);
            $('input[name="filter_product_count"]').val(value);
        }
    }
    
    $('.filter-input, .filter-select').keydown(function(e) {
        if (e.keyCode == 13) {
            filter();
        }
    });
});
//--></script>
<?php echo $footer; ?>