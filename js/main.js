function formatPrice(amount) {
    return amount.toLocaleString("vi-VN") + " ₫";
}

const CART_KEY = "minishop_cart";
const PRODUCT_CACHE_KEY = "minishop_product_cache";
const seededProducts = {
    "1": { name: "Chuột không dây M1", price: 250000, image: "images/sp01.jpg" },
    "2": { name: "Bàn phím cơ K2", price: 790000, image: "images/sp02.jpg" },
    "3": { name: "Tai nghe Bluetooth T3", price: 450000, image: "images/sp03.jpg" },
    "4": { name: "USB 64GB U4", price: 150000, image: "images/sp04.jpg" },
    "5": { name: "Webcam Full HD W5", price: 520000, image: "images/sp05.jpg" },
    "6": { name: "Đế tản nhiệt laptop D6", price: 320000, image: "images/sp06.jpg" }
};

function readProductCache() {
    const data = localStorage.getItem(PRODUCT_CACHE_KEY);
    if (!data) {
        return {};
    }

    try {
        const storedProducts = JSON.parse(data);
        if (!storedProducts || typeof storedProducts !== "object" || Array.isArray(storedProducts)) {
            throw new TypeError("Danh sách sản phẩm đã lưu không hợp lệ.");
        }

        const cachedProducts = {};
        for (const [id, product] of Object.entries(storedProducts)) {
            const price = Number(product.price);
            if (/^\d+$/.test(id) && typeof product.name === "string"
                && Number.isSafeInteger(price) && price >= 0
                && typeof product.image === "string") {
                cachedProducts[id] = { name: product.name, price, image: product.image };
            }
        }
        return cachedProducts;
    } catch (error) {
        console.error("Danh mục giỏ hàng đã lưu không hợp lệ, dữ liệu sẽ được làm mới.", error);
        localStorage.removeItem(PRODUCT_CACHE_KEY);
        return {};
    }
}

const cachedProducts = readProductCache();
const products = { ...cachedProducts, ...seededProducts };

function getCart() {
    const data = localStorage.getItem(CART_KEY);
    if (!data) {
        return {};
    }

    let storedCart;
    try {
        storedCart = JSON.parse(data);
    } catch (error) {
        console.error("Giỏ hàng đã lưu không hợp lệ, dữ liệu sẽ được làm mới.", error);
        localStorage.removeItem(CART_KEY);
        return {};
    }

    const cart = {};
    for (const id in storedCart) {
        const quantity = Number(storedCart[id]);
        if (products[id] && Number.isInteger(quantity) && quantity > 0) {
            cart[id] = quantity;
        }
    }
    return cart;
}

function saveCart(cart) {
    localStorage.setItem(CART_KEY, JSON.stringify(cart));
}

function updateCartCount() {
    const cart = getCart();
    let count = 0;
    for (const id in cart) {
        count += cart[id];
    }
    document.querySelectorAll(".cart-count").forEach(function (badge) {
        badge.textContent = count;
    });
}

function addToCart(productId, productData) {
    if (!products[productId] && productData
        && typeof productData.name === "string"
        && Number.isSafeInteger(productData.price) && productData.price >= 0
        && typeof productData.image === "string") {
        products[productId] = productData;
        cachedProducts[productId] = productData;
        localStorage.setItem(PRODUCT_CACHE_KEY, JSON.stringify(cachedProducts));
    }

    if (!products[productId]) {
        return;
    }

    const cart = getCart();
    cart[productId] = (cart[productId] || 0) + 1;
    saveCart(cart);
    updateCartCount();
}

document.querySelectorAll(".add-to-cart").forEach(function(button) {
    button.addEventListener("click", function() {
        addToCart(button.dataset.id, {
            name: button.dataset.name,
            price: Number(button.dataset.price),
            image: button.dataset.image
        });
    });
});

updateCartCount();

function renderCart() {
    const cartTable = document.querySelector(".cart-table");
    if (!cartTable) {
        return;
    }

    const cartItems = cartTable.querySelector("tbody");
    const cart = getCart();
    cartItems.replaceChildren();

    let total = 0;
    for (const id in cart) {
        const product = products[id];
        const quantity = cart[id];
        const lineTotal = product.price * quantity;
        const row = document.createElement("tr");
        const name = document.createElement("th");
        const price = document.createElement("td");
        const quantityCell = document.createElement("td");
        const lineTotalCell = document.createElement("td");
        const quantityInput = document.createElement("input");
        const removeCell = document.createElement("td");
        const removeButton = document.createElement("button");

        row.dataset.id = id;
        name.scope = "row";
        name.textContent = product.name;
        price.textContent = formatPrice(product.price);
        quantityInput.type = "number";
        quantityInput.className = "qty";
        quantityInput.min = "1";
        quantityInput.step = "1";
        quantityInput.value = quantity;
        quantityInput.setAttribute("aria-label", "Số lượng " + product.name);
        quantityCell.appendChild(quantityInput);
        lineTotalCell.textContent = formatPrice(lineTotal);
        removeButton.type = "button";
        removeButton.className = "btn btn-remove";
        removeButton.dataset.removeId = id;
        removeButton.textContent = "Xóa";
        removeButton.setAttribute("aria-label", "Xóa " + product.name + " khỏi giỏ");
        removeCell.appendChild(removeButton);
        row.append(name, price, quantityCell, lineTotalCell, removeCell);
        cartItems.appendChild(row);
        total += lineTotal;
    }

    document.querySelector("#cart-total").textContent = formatPrice(total);
    cartTable.hidden = Object.keys(cart).length === 0;
    document.querySelector("#cart-empty").hidden = !cartTable.hidden;
}

const cartTable = document.querySelector(".cart-table");
if (cartTable) {
    cartTable.addEventListener("change", function (event) {
        if (!event.target.matches(".qty")) {
            return;
        }

        const quantity = Number(event.target.value);
        if (!Number.isInteger(quantity) || quantity < 1) {
            renderCart();
            return;
        }

        const row = event.target.closest("tr");
        const cart = getCart();
        const id = row.dataset.id;
        cart[id] = quantity;
        saveCart(cart);
        updateCartCount();
        renderCart();
    });

    cartTable.addEventListener("click", function (event) {
        const removeButton = event.target.closest("[data-remove-id]");
        if (!removeButton) {
            return;
        }

        const cart = getCart();
        delete cart[removeButton.dataset.removeId];
        saveCart(cart);
        updateCartCount();
        renderCart();
    });

    renderCart();
}

const clearCartButton = document.querySelector("#clear-cart");
const clearCartDialog = document.querySelector("#clear-cart-dialog");
const cancelClearCartButton = document.querySelector("#cancel-clear-cart");
const confirmClearCartButton = document.querySelector("#confirm-clear-cart");
if (clearCartButton && clearCartDialog && cancelClearCartButton && confirmClearCartButton) {
    clearCartButton.addEventListener("click", function () {
        clearCartDialog.showModal();
    });

    cancelClearCartButton.addEventListener("click", function () {
        clearCartDialog.close();
    });

    confirmClearCartButton.addEventListener("click", function () {
        localStorage.removeItem(CART_KEY);
        updateCartCount();
        renderCart();
        clearCartDialog.close();
    });
}

const contactForm = document.querySelector("#contact-form");
if (contactForm) {
    contactForm.addEventListener("submit", function (event) {
        const message = document.querySelector("#message");
        const error = document.querySelector("#message-error");

        if (message.value.length < 10) {
            event.preventDefault();
            error.textContent = "Nội dung tin nhắn phải có ít nhất 10 ký tự.";
            message.focus();
        } else {
            error.textContent = "";
        }
    });
}

const registerForm = document.querySelector("#register-form");
if (registerForm) {
    registerForm.addEventListener("submit", function (event) {
        const password = document.querySelector("#password").value;
        const password2 = document.querySelector("#confirm-password").value;
        const error = document.querySelector ("#password-error");

        if (password !== password2) {
            event.preventDefault();
            error.textContent = "Hai mật khẩu không khớp nhau.";
        } else {
            error.textContent = "";
        }
    });
}