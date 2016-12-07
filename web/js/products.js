$(function() {
	var $loadingSpan = $('span[name=loading]'),
		$productsTable = $('#products-table'),
		$productProtoRow = $('#product-proto-tr'),
		$newProductForm = $("#new-product-form");

	$.ajax({
		url: $productsTable.data('json-url'),
		method: 'GET',
		success: function(data) {
			$('#loading-products').addClass('hidden');
			switch (data.status) {
				case 'success':
					$.each(data.products, function(index, product) {
						renderProductRow(product);
					});
					break;
				case 'error':
					alert(data.error);
					break;
			}
		}
	});

	$('#add-new-product-button').on('click', function(e) {
		var $button = $(this),
			url = $button.data('url'),
			productData = {};

		$newProductForm.find('[data-new-product]').each(function() {
			var $formField = $(this);
			productData[$formField.data('name')] = $formField.val();
		});

		displayLoaging($button);

		$.ajax({
			url: url,
			method: 'POST',
			data: productData,
			dataType: 'json',
			success: function(data) {
				switch (data.status) {
					case 'success':
						renderProductRow(data.product);
						$newProductForm.find('input[type=text], textarea').each(function() {
							var $item = $(this);
							$item.val('');
						});
						$newProductForm.find('select').each(function() {
							var $select = $(this);
							$select.val($select.find('option:first').val());
						});
						break;
					case 'error':
						alert(data.error);
						break;
				}
				hideLoaging($button);
			}
		});
	});

	$("#new-product-button").click(function(){
		$newProductForm.collapse('toggle');
	});

	function deleteProduct($button) {
		if (confirm('Are you sure?')) {
			var url = $button.closest('table').data('delete-url'),
				$row = $button.closest('tr'),
				productId = $row.data('id');

			displayLoaging($button);

			$.ajax({
				url: url + '/' + productId,
				method: 'DELETE',
				success: function() {
					$row.remove();
				}
			});
		}
	}

	function renderProductRow(productData) {
		var $newProductRow = $productProtoRow.clone();

		$newProductRow.data('id', productData.id);

		$.each(productData, function(index, value) {
			$newProductRow.find('[data-name=' + index + ']').html(value);
		});

		$newProductRow.find('.btn-danger').on('click', function() {
			deleteProduct($(this));
		});

		$productsTable.append($newProductRow);

		$newProductRow.removeClass('hidden');
	}

	function displayLoaging($button) {
		$button.addClass('hidden');
		$button.parent().append($loadingSpan);
		$loadingSpan.removeClass('hidden');
	}

	function hideLoaging($button) {
		$button.removeClass('hidden');
		$loadingSpan.addClass('hidden');
	}
});
