<?php
$total_price = 0;
?>
<div class="icon">
	<a href="javascript::" onclick="showCartPage()"><i class="fas fa-shopping-cart"></i></a>
	<span class="number"><?php echo sizeof($this->session->userdata('cart_items')); ?></span>
</div>

<!-- Cart Dropdown goes here -->
<div class="dropdown manuscript-list-dropdown corner-triangle top-right" style="display: none;"> <!-- Just remove the display none from the css to make it work -->
	<div class="list-wrapper">
		<div class="item-list">
			<ul>
				<?php foreach ($this->session->userdata('cart_items') as $cart_item):
					$manuscript_details = $this->crud_model->get_manuscript_by_id($cart_item)->row_array();
					$researcher_details = $this->user_model->get_all_user($manuscript_details['user_id'])->row_array();
					?>
					<li>
						<div class="item clearfix">
							<div class="item-image">
								<a href="">
									<img src="<?php echo $this->crud_model->get_manuscript_thumbnail_url($cart_item);?>" alt="" class="img-fluid">
								</a>
							</div>
							<div class="item-details">
								<a href="">
									<div class="manuscript-name"><?php echo $manuscript_details['title']; ?></div>
									<div class="researcher-name"><?php echo $researcher_details['first_name'].' '.$researcher_details['last_name']; ?></div>
									<div class="item-price">
										<?php if ($manuscript_details['discount_flag'] == 1):
											$total_price += $manuscript_details['discounted_price'];?>
											<span class="current-price"><?php echo currency($manuscript_details['discounted_price']); ?></span>
											<span class="original-price"><?php echo currency($manuscript_details['price']); ?></span>
										<?php else:
											$total_price += $manuscript_details['price'];?>
											<span class="current-price"><?php echo currency($manuscript_details['price']); ?></span>
										<?php endif; ?>
									</div>
								</a>
							</div>
						</div>
					</li>
				<?php endforeach; ?>
			</ul>
		</div>
		<div class="dropdown-footer">
			<div class="cart-total-price clearfix">
				<span><?php echo get_phrase('total'); ?>:</span>
				<div class="float-right">
					<span class="current-price"><?php echo currency($total_price); ?></span>
					<!-- <span class="original-price">$94.99</span> -->
				</div>
			</div>
			<a href = "<?php echo site_url('home/shopping_cart'); ?>"><?php echo get_phrase('go_to_cart'); ?></a>
		</div>
	</div>
	<div class="empty-box text-center d-none">
		<p><?php echo get_phrase('your_cart_is_empty') ?>.</p>
		<a href="">Keep Shopping</a>
	</div>
</div>

<script type="text/javascript">
function showCartPage() {
	window.location.replace("<?php echo site_url('home/shopping_cart'); ?>");
}
</script>
