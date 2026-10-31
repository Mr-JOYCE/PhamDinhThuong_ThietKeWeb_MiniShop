// ===== 1. Hàm tiện ích =====
// Định dạng số thành tiền Việt: 250000 -> "250.000 đ"
function formatPrice(amount) {
    return amount.toLocaleString("vi-VN") + " đ";
}

// ===== 3. Trang giỏ hàng: tự tính thành tiền và tổng cộng =====
function updateCartTotals(table) {
    let total = 0;
    table.querySelectorAll("tbody tr").forEach(function (row) {
        const price = Number(row.dataset.price);
        const qty = Number(row.querySelector(".qty").value);
        const lineTotal = price * qty;
        row.querySelector(".line-total").textContent = formatPrice(lineTotal);
        total += lineTotal;
    });
    document.querySelector("#cart-total").textContent = formatPrice(total);
}

const cartTable = document.querySelector(".cart-table");
if (cartTable) {
    // Tính lại mỗi khi người dùng đổi số lượng ở bất kỳ ô nào trong bảng
    cartTable.addEventListener("input", function () {
        updateCartTotals(cartTable);
    });
    // Tính lần đầu khi trang vừa mở
    updateCartTotals(cartTable);
}

// ===== 4. Form đăng ký: hai mật khẩu phải khớp nhau =====
const registerForm = document.querySelector("#register-form");
if (registerForm) {
    registerForm.addEventListener("submit", function (event) {
        const password = document.querySelector("#password").value;
        const password2 = document.querySelector("#password2").value;
        const error = document.querySelector("#password-error");

        if (password !== password2) {
            event.preventDefault();
            error.textContent = "Hai mật khẩu không khớp nhau.";
        } else {
            error.textContent = "";
        }
    });
}

let confirmationDialog;
let confirmationResult;
let approvedForm;
let approvedSubmitter;

function confirmAction(message) {
    if (!confirmationDialog) {
        confirmationDialog = document.createElement("dialog");
        confirmationDialog.className = "confirm-dialog";
        confirmationDialog.setAttribute("aria-labelledby", "confirm-title");
        confirmationDialog.setAttribute("aria-describedby", "confirm-message");
        confirmationDialog.innerHTML = `
            <div class="confirm-dialog__icon" aria-hidden="true">!</div>
            <h2 id="confirm-title">Xác nhận thao tác</h2>
            <p id="confirm-message"></p>
            <div class="confirm-dialog__actions">
                <button type="button" class="btn confirm-dialog__cancel">Quay lại</button>
                <button type="button" class="btn btn-danger confirm-dialog__accept">Xác nhận xóa</button>
            </div>
        `;
        document.body.appendChild(confirmationDialog);

        confirmationDialog.querySelector(".confirm-dialog__cancel").addEventListener("click", function () {
            confirmationDialog.close("cancel");
        });
        confirmationDialog.querySelector(".confirm-dialog__accept").addEventListener("click", function () {
            confirmationDialog.close("confirm");
        });
        confirmationDialog.addEventListener("close", function () {
            if (confirmationResult) {
                confirmationResult(confirmationDialog.returnValue === "confirm");
                confirmationResult = null;
            }
        });
    }

    confirmationDialog.querySelector("#confirm-message").textContent = message;
    confirmationDialog.querySelector(".confirm-dialog__accept").textContent =
        /xóa/i.test(message) ? "Xác nhận xóa" : "Xác nhận";

    return new Promise(function (resolve) {
        confirmationResult = resolve;
        confirmationDialog.showModal();
    });
}

document.addEventListener("submit", function (event) {
    const form = event.target;
    if (!(form instanceof HTMLFormElement)) {
        return;
    }

    const submitter = event.submitter;
    if (approvedForm === form && approvedSubmitter === submitter) {
        approvedForm = null;
        approvedSubmitter = null;
        return;
    }

    const message = submitter && submitter.dataset.confirm
        ? submitter.dataset.confirm
        : form.dataset.confirm;
    if (!message) {
        return;
    }

    event.preventDefault();
    confirmAction(message).then(function (confirmed) {
        if (confirmed) {
            approvedForm = form;
            approvedSubmitter = submitter;
            if (submitter && submitter.form === form) {
                form.requestSubmit(submitter);
            } else {
                form.requestSubmit();
            }
        }
    });
});
