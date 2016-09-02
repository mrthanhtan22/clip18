<style>table#cart_ccontents td{padding:10px;border:1px solid #ccc}</style>
<div class="box-center">
<!-- The box-center product-->
<div class="tittle-box-center">
<h2>Thong tin gio hang ( Có <?php echo $total_items?> sản phẩm)</h2>
</div>
<div class="box-content-center product"><!-- The box-content-center -->
	<?php if($total_items > 0):?>
      <form action="<?php echo base_url('cart/update')?>" method="post">
<div class="table-responsive">
	<table id="cart_ccontents">
		<thead>
			<tr>
			   <th>Sản phẩm</th>
           <th>Giá bán</th>
           <th>Số lượng</th>
           <th>Tổng số</th>
           <th>Xóa</th>
			</tr>
		</thead>
			<tbody>
				<?php $total_amount = 0; ?>
				<?php foreach ($carts as $row ): ?>
				<?php $total_amount += $row['subtotal']; ?>
				<tr>
					<td><?php echo $row['name'] ?></td>
					<td><?php echo number_format($row['price']) ?>đ</td>
					<td><input type="text" name="qty_<?php echo $row['id']?>" value="<?php echo $row['qty'] ?>"></td>
					<td><?php echo number_format($row['subtotal']) ?>đ</td>
					<td>
					<a href="<?php echo base_url('cart/del/'.$row['id']) ?>">Xóa</a>
					</td>
				</tr>
				<?php endforeach; ?>
				<tr>
					<td colspan="5">
						Tong tien thanh toan: <b style="color:red"><?php echo number_format($total_amount) ?>d</b>
						<a href="<?php echo base_url('cart/del')?>">Xoa toan bo</a>
					</td>
				</tr>
				<tr>
					<td colspan="5">
					<button type="submit">Cập nhât</button>
					</td>
				</tr>
			</tbody>
		
	</table>
</div>
</form>
<?php else: ?>
<h4>Không có sản phẩm nào trong giỏ hàng</h4>
<?php endif;?>



</div>
</div>