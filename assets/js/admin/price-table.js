/*bmfm_price_table_params */
jQuery( function( $ ) {
	var obj = {};
	var Price_Table = {
		init: function() {			
			this.set_price_table_object_handsontable();
			
			jQuery( document ).on('click','.bmfm-insert-row',this.insert_price_table_row);
			jQuery( document ).on('click','.bmfm-insert-col',this.insert_price_table_col);
			jQuery( document ).on('click','.checkbox-element-row',this.add_class_on_element_row_checked);
			jQuery( document ).on('click','.checkbox-element-col',this.add_class_on_element_col_checked);
			jQuery( document ).on('click','.bmfm-delete-handsontable',this.delete_handsontable);
			jQuery( document ).on('click','.bmfm-delete-all-handsontable',this.delete_all_handsontable);
			jQuery( document ).on('click','.bmfm-save-handsontable',this.save_handsontable);
		},
		set_price_table_object_handsontable:function(){
  			document.getElementById("bmfm_blinds_price_handsontable").innerHTML = "";	
  			var blinds_price_handsontable = document.getElementById("bmfm_blinds_price_handsontable"),
				$stored_data         = bmfm_price_table_params.stored_price_table_data,
				$stored_data_in_cm   = bmfm_price_table_params.stored_price_table_data_in_cm,
				$stored_data_in_inch = bmfm_price_table_params.stored_price_table_data_in_inch;
				if('cm' == $('input[name="bmfm_default_unit"]:checked').val()){
				    $stored_data = $stored_data_in_cm;
				}else if('inch' == $('input[name="bmfm_default_unit"]:checked').val()){
				    $stored_data = $stored_data_in_inch;
				}
				
 	 		if('' == $stored_data){
    			$stored_data = [
      				['Drop/Width'],
    			];
  			}
   
  			obj = new Handsontable(blinds_price_handsontable, {
    			startRows: 2,
    			startCols: 2,
    			rowHeaders: true,
    			colHeaders: true,
    			colHeaders: ['', ''],
    			rowHeaders: ['', ''],
				contextMenu: ['copy', 'cut'],
    			data: $stored_data,
				colWidths: 84,
				width: '100%',
				height: 350,
				rowHeights: 23,
				fixedRowsTop: 1,
  				fixedColumnsLeft: 1,
    			readOnly: false,
    			cells(row, col, prop) {
      				if (row == 0 && col == 0) {
          				return {
            				readOnly: true,
          				};
        			}
        			if (row == 0) {
          				return {
            				className: 'first-col',
          				};
        			}
        			if (col == 0) {
          				return {
            				className: 'first-row',
          				};
        			}
    			},
    			licenseKey: "non-commercial-and-evaluation",
    			beforeOnCellMouseDown: function(e, coords) {
      				if (coords.row < 0 || coords.col < 0) {
        				e.stopImmediatePropagation();
      				}
    			},
  			});

  			var row = obj.countRows(),
    			col = obj.countCols(),
				$col_checkbox = [];
  			for (i = 0; i < col; i++) {
      			if (i == 0) {
        			$col_checkbox.push('');
      			} else {
        			$col_checkbox.push('<input type="checkbox" class="checkbox-element-col" data-col_index="' + i + '">');
      			}
  			}

  			var $row_checkbox = [];
  			for (i = 0; i < row; i++) {
      			if (i == 0 ) {
        			$row_checkbox.push('');
      			} else {
        			$row_checkbox.push('<input type="checkbox" class="checkbox-element-row" data-row_index="' + i + '">');
      			}
   			}

   			obj.updateSettings({
   		 		colHeaders: $col_checkbox,
    			rowHeaders: $row_checkbox,
   			});
			var $total_cols = obj.countCols() - 1;
			$total_cols = (parseInt($total_cols) * 84) + 129;
			$('.bmfm-blinds-price-table-buttons').css('width',$total_cols+"px");
		},
		insert_price_table_row:function(){
		  var current_row = obj.countRows(),
    			col = obj.countCols();
	
		  if (col <= 2) { 
    			obj.alter('insert_col_start', col, 1);
				obj.alter('insert_row_above', 10);
		  }else{
				obj.alter('insert_row_above', current_row);
		  }

    	  col = obj.countCols();
    	  var $col_checkbox = [];
	  
    	  for (i = 0; i < col; i++) {
      		if (i == 0 ) {
        		$col_checkbox.push('');
			} else {
        		$col_checkbox.push('<input type="checkbox" class="checkbox-element-col" data-col_index="' + i + '">');
      		}
    	  }

    	  var row = obj.countRows(),
      		$row_checkbox = [];

    	  for (i = 0; i < row; i++) {
      		if (i == 0 ) {
        		$row_checkbox.push('');
      		} else {
        		$row_checkbox.push('<input type="checkbox" class="checkbox-element-row" data-row_index="' + i + '">');
      		}
    	  }

    	  $total_row = obj.countRows();
    	  $total_col = obj.countCols();
    	  obj.updateSettings({
      		rowHeaders: true,
      		colHeaders: true,
      		colHeaders: $col_checkbox,
      	    rowHeaders: $row_checkbox,
      		readOnly: false,
      		cells(row, col, prop) {
        		if (row == 0 && col == 0) {
          			return {
            			readOnly: true,
          			};
        		}
        		if (row == 0) {
          			return {
            			className: 'first-col',
          			};
        		}
        		if (col == 0) {
          			return {
            			className: 'first-row',
          			};
        		}
      		 },
    	   });
    	   return false;
		},
		insert_price_table_col:function(){
		  var col = obj.countRows();
    	  if (col <= 2) {
      		obj.alter('insert_row_above', col, 1);
      		obj.alter('insert_col_start', col, 1);
      		obj.setDataAtCell(col, 0, '');
      		obj.setDataAtCell(col, 1, '');
    	  } else {
      		obj.alter('insert_col_start', obj.countCols(), 1);
    	  }
	  
    	   col = obj.countCols();
    		var $col_checkbox = [];
    		for (i = 0; i < col; i++) {
      			if (i == 0 ) {
        			$col_checkbox.push('');
      			} else {
        			$col_checkbox.push('<input type="checkbox" class="checkbox-element-col" data-col_index="' + i + '">');
      			}
    		}
    		var row = obj.countRows(),
      			$row_checkbox = [];
    		for (i = 0; i < row; i++) {
      			if (i == 0 ) {
        			$row_checkbox.push('');
      			} else {
       			    $row_checkbox.push('<input type="checkbox" class="checkbox-element-row" data-row_index="' + i + '">');
     		    }
    		}

    		$total_row = row;
    		$total_col = col;
    		obj.updateSettings({
      			rowHeaders: true,
      			colHeaders: true,
      			colHeaders: $col_checkbox,
      			rowHeaders: $row_checkbox,
      			readOnly: false,
      			cells(row, col, prop) {
        			if (row == 0 && col == 0) {
          				return {
            				readOnly: true,
          				};
        			}
      			},
    		});
			Price_Table.button_alignment_price_table(obj);
    		return false;
		},
		add_class_on_element_row_checked:function(){
			jQuery(this).removeClass('row-selected');
    		if (jQuery(this).is(':checked')) {
      			jQuery(this).addClass('row-selected');
    		}
		},
		add_class_on_element_col_checked:function(){
			jQuery(this).removeClass('col-selected');
   	 		if (jQuery(this).is(':checked')) {
      			jQuery(this).addClass('col-selected');
    		}
		},
		delete_handsontable:function(){		
			var $remove_col  = [];		
			var $remove_row  = [];	
			if(!confirm(bmfm_price_table_params.confirm_msg)){
				return false;
			  }
			if (jQuery('.row-selected').length > 0) {
      			jQuery('.row-selected').each(function() {
					var index = jQuery(this).data('row_index');	
					$remove_row.push(index);
      			});
    	    }
    		if (jQuery('.col-selected').length > 0) {
				jQuery('.col-selected').each(function() {
					var index = jQuery(this).data('col_index');	
					$remove_col.push(index);
      		    });
			}
				var data = { 
						action:'bmfm_delete_selected_price_table_row_column',
						remove_col: $remove_col,
						remove_row: $remove_row,
						price_table_data:obj.getData(),
						security:bmfm_admin_params.delete_selected_price_table_row_column_nonce,
				};

				Price_Table.block_ui($('.bmfm-blinds-price-table-wrapper'));
				jQuery.ajax({
					url: bmfm_price_table_params.ajax_url,
					data: data,
					type: 'POST',
					success: function( response ) {
						if(response.data.success){
							Price_Table.unblock_ui($('.bmfm-blinds-price-table-wrapper'));
							var price_table_data=[];
							$.each(response.data.price_table_data,function(i, price_table_values){
								price_table_data.push([]);
    							$.each(price_table_values,function(j, per_price_table_value){
										price_table_data[i].push(per_price_table_value);
								});
							});
							obj.updateSettings({
								data: price_table_data,
							});
							Price_Table.button_alignment_price_table(obj);
						}else if(response.data.error){
							Price_Table.unblock_ui($('.bmfm-blinds-price-table-wrapper'));
						}
				    }
				});
		},
		delete_all_handsontable:function(){
			if(!confirm(bmfm_price_table_params.confirm_msg)){
		 		 return false;
	  		}
	  
	  		obj.updateSettings({
		  		data: [['Drop/Width']],
	  		});
			Price_Table.button_alignment_price_table(obj);
		},
		save_handsontable:function(event){
			event.preventDefault();
    		if(!confirm(bmfm_price_table_params.confirm_msg)){
      			return false;
    		}
    		var data={
				action:'bmfm_save_price_table_data',
        		price_table_data:obj.getData(),
				markup:$('.bmfm-markup-fee').val(),
				default_unit:$('input[name="bmfm_default_unit"]:checked').val(),
        		product_type_id:bmfm_price_table_params.product_type_id,
				security:bmfm_price_table_params.save_price_table_nonce,
			};
	  		Price_Table.block_ui($('.bmfm-blinds-price-table-wrapper'));
    	    jQuery.ajax({
				url: bmfm_price_table_params.ajax_url,
				data: data,
				type: 'POST',
				success: function( response ) {
					if(response.data.success){
            			alert(response.data.msg);
						Price_Table.unblock_ui($('.bmfm-blinds-price-table-wrapper'));
					}else if(response.data.error){
						alert(response.data.error);
						Price_Table.unblock_ui($('.bmfm-blinds-price-table-wrapper'));
					}
			  	}
			});
			return false;
		},
		button_alignment_price_table:function(obj){
			var $total_cols = obj.countCols() - 1;
			$total_cols = (parseInt($total_cols) * 84) + 129;
			$('.bmfm-blinds-price-table-buttons').css('width',$total_cols+"px");
		},
		block_ui:function($id){
			$( $id ).block({
					message: null,
					overlayCSS: {
						background: '#fff',
						opacity: 0.6
					}
			});
		},
		unblock_ui:function($id){
			$( $id ).unblock();
		},
	};
	Price_Table.init();
} );
