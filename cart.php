<?php
require __DIR__ . '/includes/init.php';
$pageTitle = 'Giỏ hàng';
require __DIR__ . '/includes/header.php';
?>
<section>
    <h2>Giỏ hàng</h2>
    <p id="cart-empty">Giỏ hàng đang trống. Hãy chọn sản phẩm bạn muốn mua.</p>
    <div class="cart-table-wrap">
        <table class="cart-table" hidden>
            <thead>
                <tr>
                    <th scope="col">Sản phẩm</th>
                    <th scope="col">Đơn giá</th>
                    <th scope="col">Số lượng</th>
                    <th scope="col">Thành tiền</th>
                    <th scope="col"><span class="visually-hidden">Thao tác</span></th>
                </tr>
            </thead>
            <tbody></tbody>
            <tfoot>
                <tr>
                    <th scope="row" colspan="3">Tổng cộng</th>
                    <td id="cart-total">0 ₫</td>
                    <td></td>
                </tr>
            </tfoot>
        </table>
    </div>
    <p>
        <button type="button" class="btn btn-accent" id="clear-cart">Xóa giỏ hàng</button>
        <button type="button" class="btn" disabled>Đặt hàng</button>
    </p>

    <dialog class="confirm-dialog" id="clear-cart-dialog" aria-labelledby="clear-cart-title"
        aria-describedby="clear-cart-description">
        <div class="confirm-dialog__mark" aria-hidden="true">!</div>
        <h2 id="clear-cart-title">Xóa toàn bộ giỏ hàng?</h2>
        <p id="clear-cart-description">Bạn chắc chắn muốn xóa giỏ hàng? Sản phẩm đã chọn sẽ bị xóa khỏi giỏ.</p>
        <div class="confirm-dialog__actions">
            <button type="button" class="btn btn-cancel" id="cancel-clear-cart" autofocus>Giữ lại giỏ hàng</button>
            <button type="button" class="btn btn-confirm-delete" id="confirm-clear-cart">Xóa giỏ hàng</button>
        </div>
    </dialog>
</section>
<?php require __DIR__ . '/includes/footer.php'; ?>
