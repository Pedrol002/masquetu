const menuItems = [
    {
        id: 1,
        name: "Tênis Nike Revolution 7 Masculino",
        description: "Se você busca uma corrida mais confortável e de alto desempenho, o Nike Revolution 7 é a escolha ideal.",
        price: 309.99,
        image: "imgs/tenis1.jpg",
    },
    {
        id: 2,
        name: "Tênis Mizuno Wave Frontier 15 - Masculino",
        description: "O Mizuno Wave Frontier 15 é um tênis de corrida de alta performance projetado para corredores que buscam uma experiência de corrida suave e confortável.",
        price: 469.99,
        image: "imgs/tenis2.jpg",
    },
    {
        id: 3,
        name: "Tênis ASICS Gel-Nagoya 7 Masculino",
        description: "O Tênis ASICS GEL-Nagoya 7 masculino foi totalmente repaginado, trazendo um design moderno aliado a tecnologias avançadas que atendem às necessidades dos corredores mais exigentes.",
        price: 379.99,
        image: "imgs/tenis3.jpg",
    },
    {
        id: 4,
        name: "Tênis adidas RunFalcon 5 Feminino",
        description: "O Tênis adidas RunFalcon 5 Feminino combina estilo e conforto para o seu dia a dia.",
        price: 349.99,
        image: "imgs/tenis1.jpg",
    },
    {
        id: 5,
        name: "Tênis Mizuno Wave Dynasty 6 Masculino",
        description: "Experimente o máximo em desempenho e conforto com o Tênis Mizuno Wave Dynasty 6.",
        price: 349.99,
        image: "imgs/tenis2.jpg",
    },
];

let cart = [];
let freteValue = 0;

const formatCurrency = (value) => {
    return value.toFixed(2).replace(".", ",");
};

function initMenu() {
    const menuContainer = document.getElementById("menuContainer");
    menuContainer.innerHTML = "";
    menuItems.forEach((item) => {
        const menuItem = document.createElement("div");
        menuItem.className = "menu-item";
        menuItem.innerHTML = `
            <img src="${item.image}" alt="${item.name}">
            <div class="item-content">
                <h3 class="item-title">${item.name}</h3>
                <p class="item-description">${item.description}</p>
                <div class="item-footer">
                    <span class="item-price">R$${formatCurrency(item.price)}</span>
                    <button class="add-to-cart" onclick="addToCart(${item.id})">
                        Adicionar <i class="fas fa-cart-plus"></i>
                    </button>
                </div>
            </div>
        `;
        menuContainer.appendChild(menuItem);
    });
}

function updateCart() {
    const cartItemsList = document.getElementById("cartItems");
    const cartTotalSpan = document.getElementById("cartTotal");
    const cartCountSpan = document.querySelector(".cart-count");
    
    cartItemsList.innerHTML = "";
    let subtotal = 0;
    
    cart.forEach((item) => {
        subtotal += item.price * item.quantity;
        const li = document.createElement("li");
        li.className = "cart-item";
        li.innerHTML = `
            <span>${item.name} (x${item.quantity})</span>
            <div>
                <button onclick="adjustQuantity(${item.id}, -1)">-</button>
                <span>R$${formatCurrency(item.price * item.quantity)}</span>
                <button onclick="adjustQuantity(${item.id}, 1)">+</button>
            </div>
        `;
        cartItemsList.appendChild(li);
    });

    const total = subtotal + freteValue;
    cartTotalSpan.textContent = formatCurrency(total);
    cartCountSpan.textContent = cart.reduce((sum, item) => sum + item.quantity, 0);
}

function addToCart(itemId) {
    const itemToAdd = menuItems.find((item) => item.id === itemId);
    const existingItem = cart.find((item) => item.id === itemId);

    if (existingItem) {
        existingItem.quantity++;
    } else {
        cart.push({ ...itemToAdd,
            quantity: 1
        });
    }

    updateCart();
}

function adjustQuantity(itemId, change) {
    const item = cart.find((item) => item.id === itemId);

    if (item) {
        item.quantity += change;

    updateCart();
    toggleCart();
}

        if (item.quantity <= 0) {
            cart = cart.filter((i) => i.id !== itemId);
        }
    }

    updateCart();


function toggleCart() {
    document.querySelector(".cart-sidebar").classList.toggle("active");
}

function realizarPedido() {
    if (cart.length === 0) {
        alert("Não há itens no carrinho para finalizar a compra.");
        return;
    }
    const subtotal = cart.reduce((sum, item) => sum + item.price * item.quantity, 0);
    const total = subtotal + freteValue;
    
    alert(`Pedido realizado! Total: R$${formatCurrency(total)}`);
    cart = [];
    freteValue = 0;
    updateCart();
    toggleCart();
}

document.querySelector(".menu-hamburguer").addEventListener("click", function () {
    document.querySelector(".nav").classList.toggle("active");
});

document.getElementById("searchInput").addEventListener("input", (e) => {
    const searchTerm = e.target.value.toLowerCase();
    document.querySelectorAll(".menu-item").forEach((item) => {
        const title = item.querySelector(".item-title").textContent.toLowerCase();
        item.style.display = title.includes(searchTerm) ? "block" : "none";
    });
});

document.addEventListener("DOMContentLoaded", initMenu);

const cepInput = document.getElementById("cepInput");
const calcFreteBtn = document.getElementById("calcFreteBtn");
const freteInfo = document.getElementById("freteInfo");
const enderecoFreteSpan = document.getElementById("enderecoFrete");
const valorFreteSpan = document.getElementById("valorFrete");
const cepError = document.getElementById("cepError");
const loadingOverlay = document.getElementById("loadingOverlay");

if (cepInput) {
    cepInput.addEventListener("input", (e) => {
        let value = e.target.value.replace(/\D/g, "");
        if (value.length > 5) {
            value = value.substring(0, 5) + "-" + value.substring(5, 8);
        }
        e.target.value = value;
    });
}

if (calcFreteBtn) {
    calcFreteBtn.addEventListener("click", async () => {
        const cep = cepInput.value.replace(/\D/g, "");

        if (cep.length !== 8) {
            cepError.textContent = "CEP inválido.";
            cepError.style.display = "block";
            return;
        }
        cepError.style.display = "none";

        loadingOverlay.style.display = "flex";

        try {
            const response = await fetch(`API/cep_api.php?cep=${cep}`);
            const data = await response.json();

            if (data.erro) {
                enderecoFreteSpan.textContent = "CEP não encontrado.";
                valorFreteSpan.textContent = "N/A";
                freteInfo.classList.add("show");
                freteValue = 0;
                updateCart();
                return;
            }

            freteValue = data.uf === "SP" ? 15.0 : 35.0;

            enderecoFreteSpan.textContent = `${data.logradouro}, ${data.bairro}, ${data.localidade} - ${data.uf}`;
            valorFreteSpan.textContent = `R$${formatCurrency(freteValue)}`;
            freteInfo.classList.add("show");
            updateCart();
        } catch (error) {
            console.error("Erro ao buscar CEP:", error);
            cepError.textContent = "Erro ao calcular frete. Tente novamente.";
            cepError.style.display = "block";
            freteInfo.classList.remove("show");
        } finally {
            loadingOverlay.style.display = "none";
        }
    });
}
    freteValue = 0;
