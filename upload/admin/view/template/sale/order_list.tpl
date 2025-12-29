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
      <h1><img src="view/image/order.png" alt="" /> <?php echo $heading_title; ?></h1>
      <div class="buttons">
        <a onclick="$('#form').attr('action', '<?php echo $invoice; ?>'); $('#form').attr('target', '_blank'); $('#form').submit();" class="button"><?php echo $button_invoice; ?></a>
        <a href="<?php echo $insert; ?>" class="button"><?php echo $button_insert; ?></a>
        <a onclick="$('#form').attr('action', '<?php echo $delete; ?>'); $('#form').attr('target', '_self'); $('#form').submit();" class="button"><?php echo $button_delete; ?></a>
      </div>
    </div>
    <div class="content">
      <form action="" method="post" enctype="multipart/form-data" id="form">
        <table class="list">
          <thead>
            <tr>
              <td width="1" style="text-align: center;"><input type="checkbox" onclick="$('input[name*=\'selected\']').attr('checked', this.checked);" /></td>
              <td class="right"><?php if ($sort == 'o.order_id') { ?>
                <a href="<?php echo $sort_order; ?>" class="<?php echo strtolower($order); ?>"><?php echo $column_order_id; ?></a>
                <?php } else { ?>
                <a href="<?php echo $sort_order; ?>"><?php echo $column_order_id; ?></a>
                <?php } ?></td>
              <td class="left"><?php if ($sort == 'customer') { ?>
                <a href="<?php echo $sort_customer; ?>" class="<?php echo strtolower($order); ?>"><?php echo $column_customer; ?></a>
                <?php } else { ?>
                <a href="<?php echo $sort_customer; ?>"><?php echo $column_customer; ?></a>
                <?php } ?></td>
              <td class="left"><?php if ($sort == 'status') { ?>
                <a href="<?php echo $sort_status; ?>" class="<?php echo strtolower($order); ?>"><?php echo $column_status; ?></a>
                <?php } else { ?>
                <a href="<?php echo $sort_status; ?>"><?php echo $column_status; ?></a>
                <?php } ?></td>
              <td class="right"><?php if ($sort == 'o.total') { ?>
                <a href="<?php echo $sort_total; ?>" class="<?php echo strtolower($order); ?>"><?php echo $column_total; ?></a>
                <?php } else { ?>
                <a href="<?php echo $sort_total; ?>"><?php echo $column_total; ?></a>
                <?php } ?></td>
              <td class="left"><?php if ($sort == 'o.date_added') { ?>
                <a href="<?php echo $sort_date_added; ?>" class="<?php echo strtolower($order); ?>"><?php echo $column_date_added; ?></a>
                <?php } else { ?>
                <a href="<?php echo $sort_date_added; ?>"><?php echo $column_date_added; ?></a>
                <?php } ?></td>
              <td class="left"><?php if ($sort == 'o.date_modified') { ?>
                <a href="<?php echo $sort_date_modified; ?>" class="<?php echo strtolower($order); ?>"><?php echo $column_date_modified; ?></a>
                <?php } else { ?>
                <a href="<?php echo $sort_date_modified; ?>"><?php echo $column_date_modified; ?></a>
                <?php } ?></td>
              <td class="right"><?php echo $column_action; ?></td>
            </tr>
          </thead>
          <tbody>
            <tr class="filter">
              <td></td>
              <td align="right"><input type="text" name="filter_order_id" value="<?php echo $filter_order_id; ?>" size="4" style="text-align: right;" /></td>
              <td><input type="text" name="filter_customer" value="<?php echo $filter_customer; ?>" /></td>
              <td><select name="filter_order_status_id">
                  <option value="*"></option>
                  <?php if ($filter_order_status_id == '0') { ?>
                  <option value="0" selected="selected"><?php echo $text_missing; ?></option>
                  <?php } else { ?>
                  <option value="0"><?php echo $text_missing; ?></option>
                  <?php } ?>
                  <?php foreach ($order_statuses as $order_status) { ?>
                  <?php if ($order_status['order_status_id'] == $filter_order_status_id) { ?>
                  <option value="<?php echo $order_status['order_status_id']; ?>" selected="selected"><?php echo $order_status['name']; ?></option>
                  <?php } else { ?>
                  <option value="<?php echo $order_status['order_status_id']; ?>"><?php echo $order_status['name']; ?></option>
                  <?php } ?>
                  <?php } ?>
                </select></td>
              <td align="right"><input type="text" name="filter_total" value="<?php echo $filter_total; ?>" size="4" style="text-align: right;" /></td>
              <td><input type="text" name="filter_date_added" value="<?php echo $filter_date_added; ?>" size="12" class="date" /></td>
              <td><input type="text" name="filter_date_modified" value="<?php echo $filter_date_modified; ?>" size="12" class="date" /></td>
              <td align="right"><a onclick="filter();" class="button"><?php echo $button_filter; ?></a></td>
            </tr>
            <?php if ($orders) { ?>
            <?php foreach ($orders as $order) { ?>
            <tr>
              <td style="text-align: center;"><?php if ($order['selected']) { ?>
                <input type="checkbox" name="selected[]" value="<?php echo $order['order_id']; ?>" checked="checked" />
                <?php } else { ?>
                <input type="checkbox" name="selected[]" value="<?php echo $order['order_id']; ?>" />
                <?php } ?></td>
              <td class="right"><?php echo $order['order_id']; ?></td>
              <td class="left"><?php echo $order['customer']; ?></td>
              <td class="left"><?php echo $order['status']; ?></td>
              <td class="right"><?php echo $order['total']; ?></td>
              <td class="left"><?php echo $order['date_added']; ?></td>
              <td class="left"><?php echo $order['date_modified']; ?></td>
              <td class="right">
                <?php foreach ($order['action'] as $action) { ?>
                [ <a href="<?php echo $action['href']; ?>" <?php if (isset($action['onclick'])) { ?>onclick="<?php echo $action['onclick']; ?>"<?php } ?>><?php echo $action['text']; ?></a> ]
                <?php } ?>
              </td>
            </tr>
            <?php } ?>
            <?php } else { ?>
            <tr>
              <td class="center" colspan="8"><?php echo $text_no_results; ?></td>
            </tr>
            <?php } ?>
          </tbody>
        </table>
      </form>
      <div class="pagination"><?php echo $pagination; ?></div>
    </div>
  </div>
</div>

<!-- Modal Structure -->
<div id="productsModal" style="display: none; position: fixed; top: 50%; left: 50%; transform: translate(-50%, -50%); width: 800px; max-width: 90%; background: white; border: 2px solid #ccc; border-radius: 5px; box-shadow: 0 4px 20px rgba(0,0,0,0.2); z-index: 10001; max-height: 80vh; overflow-y: auto;">
  <div style="padding: 20px;">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 15px; border-bottom: 1px solid #eee; padding-bottom: 10px;">
      <h2 style="margin: 0; color: #333;">Order Products - Order ID: <span id="modalOrderId" style="color: #0066cc;"></span></h2>
      <button onclick="closeModal()" style="background: #ff4444; color: white; border: none; padding: 5px 10px; border-radius: 3px; cursor: pointer;">✕ Close</button>
    </div>
    
    <div id="loading" style="text-align: center; padding: 40px 20px;">
      <img src="view/image/loading.gif" alt="Loading..." style="margin-bottom: 10px;" /><br>
      <span style="color: #666;">Loading products...</span>
    </div>
    
    <div id="productsContent" style="display: none;">
      <div id="productsError" style="display: none; color: #cc0000; padding: 20px; text-align: center; background: #fff0f0; border-radius: 3px; margin-bottom: 15px;"></div>
      
      <table style="width: 100%; border-collapse: collapse; margin-top: 10px;">
        <thead>
          <tr style="background: #f5f5f5;">
            <th style="border: 1px solid #ddd; padding: 10px; text-align: left; font-weight: bold;">Product ID</th>
            <th style="border: 1px solid #ddd; padding: 10px; text-align: left; font-weight: bold;">Product Name</th>
            <th style="border: 1px solid #ddd; padding: 10px; text-align: left; font-weight: bold;">Price</th>
            <th style="border: 1px solid #ddd; padding: 10px; text-align: left; font-weight: bold;">Quantity</th>
            <th style="border: 1px solid #ddd; padding: 10px; text-align: left; font-weight: bold;">Total</th>
          </tr>
        </thead>
        <tbody id="productsTableBody">
          <!-- Products will be loaded here -->
        </tbody>
      </table>
      
      <div id="productsSummary" style="margin-top: 15px; padding: 10px; background: #f9f9f9; border-radius: 3px; display: none;">
        <strong>Total Items: <span id="totalItems">0</span></strong>
      </div>
    </div>
  </div>
</div>

<div id="modalOverlay" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.5); z-index: 10000;"></div>

<script type="text/javascript">
// View Products Function - uses jQuery for dynamic loading
function viewProducts(order_id) {
    console.log('Opening modal for order:', order_id);
    
    // Set order ID in modal
    $('#modalOrderId').text(order_id);
    
    // Show modal and overlay
    $('#productsModal').show();
    $('#modalOverlay').show();
    
    // Reset modal content
    $('#loading').show();
    $('#productsContent').hide();
    $('#productsError').hide().empty();
    $('#productsTableBody').empty();
    $('#productsSummary').hide();
    
    // AJAX request to load products
    $.ajax({
        url: 'index.php?route=sale/order/getOrderProducts&token=<?php echo $token; ?>&order_id=' + order_id,
        type: 'GET',
        dataType: 'json',
        success: function(response) {
            console.log('AJAX Response:', response);
            
            if (response.success && response.products && response.products.length > 0) {
                var html = '';
                var totalItems = 0;
                
                $.each(response.products, function(index, product) {
                    html += '<tr>';
                    html += '<td style="border: 1px solid #ddd; padding: 8px;">' + product.product_id + '</td>';
                    html += '<td style="border: 1px solid #ddd; padding: 8px;">' + product.name + '</td>';
                    html += '<td style="border: 1px solid #ddd; padding: 8px;">' + product.price + '</td>';
                    html += '<td style="border: 1px solid #ddd; padding: 8px;">' + product.quantity + '</td>';
                    html += '<td style="border: 1px solid #ddd; padding: 8px;">' + product.total + '</td>';
                    html += '</tr>';
                    
                    totalItems += parseInt(product.quantity);
                });
                
                $('#productsTableBody').html(html);
                $('#totalItems').text(totalItems);
                $('#productsSummary').show();
                $('#loading').hide();
                $('#productsContent').show();
            } else {
                var errorMsg = response.error || 'No products found for this order';
                $('#productsError').text(errorMsg).show();
                $('#loading').hide();
                $('#productsContent').show();
            }
        },
        error: function(xhr, status, error) {
            console.log('AJAX Error:', error);
            $('#productsError').text('Error loading products: ' + error).show();
            $('#loading').hide();
            $('#productsContent').show();
        },
        complete: function() {
            console.log('AJAX request completed');
        }
    });
}

// Close modal function
function closeModal() {
    $('#productsModal').hide();
    $('#modalOverlay').hide();
}

// Close modal when clicking on overlay
$('#modalOverlay').click(closeModal);

// Close modal with ESC key
$(document).keydown(function(e) {
    if (e.keyCode === 27) { // ESC key
        closeModal();
    }
});

// Prevent modal close when clicking inside modal
$('#productsModal').click(function(e) {
    e.stopPropagation();
});
</script>

<script type="text/javascript"><!--
function filter() {
    url = 'index.php?route=sale/order&token=<?php echo $token; ?>';
    
    var filter_order_id = $('input[name=\'filter_order_id\']').attr('value');
    
    if (filter_order_id) {
        url += '&filter_order_id=' + encodeURIComponent(filter_order_id);
    }
    
    var filter_customer = $('input[name=\'filter_customer\']').attr('value');
    
    if (filter_customer) {
        url += '&filter_customer=' + encodeURIComponent(filter_customer);
    }
    
    var filter_order_status_id = $('select[name=\'filter_order_status_id\']').attr('value');
    
    if (filter_order_status_id != '*') {
        url += '&filter_order_status_id=' + encodeURIComponent(filter_order_status_id);
    } 

    var filter_total = $('input[name=\'filter_total\']').attr('value');

    if (filter_total) {
        url += '&filter_total=' + encodeURIComponent(filter_total);
    } 
    
    var filter_date_added = $('input[name=\'filter_date_added\']').attr('value');
    
    if (filter_date_added) {
        url += '&filter_date_added=' + encodeURIComponent(filter_date_added);
    }
    
    var filter_date_modified = $('input[name=\'filter_date_modified\']').attr('value');
    
    if (filter_date_modified) {
        url += '&filter_date_modified=' + encodeURIComponent(filter_date_modified);
    }
            
    location = url;
}
//--></script>  

<script type="text/javascript"><!--
$(document).ready(function() {
    $('.date').datepicker({dateFormat: 'yy-mm-dd'});
});
//--></script> 

<script type="text/javascript"><!--
$('#form input').keydown(function(e) {
    if (e.keyCode == 13) {
        filter();
    }
});
//--></script> 

<script type="text/javascript"><!--
$.widget('custom.catcomplete', $.ui.autocomplete, {
    _renderMenu: function(ul, items) {
        var self = this, currentCategory = '';
        
        $.each(items, function(index, item) {
            if (item.category != currentCategory) {
                ul.append('<li class="ui-autocomplete-category">' + item.category + '</li>');
                
                currentCategory = item.category;
            }
            
            self._renderItem(ul, item);
        });
    }
});

$('input[name=\'filter_customer\']').catcomplete({
    delay: 500,
    source: function(request, response) {
        $.ajax({
            url: 'index.php?route=sale/customer/autocomplete&token=<?php echo $token; ?>&filter_name=' +  encodeURIComponent(request.term),
            dataType: 'json',
            success: function(json) {    
                response($.map(json, function(item) {
                    return {
                        category: item.customer_group,
                        label: item.name,
                        value: item.customer_id
                    }
                }));
            }
        });
    }, 
    select: function(event, ui) {
        $('input[name=\'filter_customer\']').val(ui.item.label);
                
        return false;
    },
    focus: function(event, ui) {
        return false;
    }
});
//--></script>

<?php echo $footer; ?>