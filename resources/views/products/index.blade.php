<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SneakerShop - Tu tienda de zapatillas</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700&display=swap');
        body { font-family: 'Poppins', sans-serif; }
    </style>
</head>
<body class="bg-gray-50" x-data="{ 
    cart: [], 
    isCartOpen: false,
    addToCart(sneaker) {
        const item = this.cart.find(i => i.id === sneaker.id);
        if (item) {
            item.quantity++;
        } else {
            this.cart.push({...sneaker, quantity: 1});
        }
    },
    removeFromCart(id) {
        this.cart = this.cart.filter(i => i.id !== id);
    },
    get cartTotal() {
        return this.cart.reduce((sum, item) => sum + (item.price * item.quantity), 0);
    },
    get cartCount() {
        return this.cart.reduce((sum, item) => sum + item.quantity, 0);
    }
}">
    <!-- Navbar -->
    <nav class="bg-white shadow-md sticky top-0 z-50">
        <div class="container mx-auto px-4 py-4 flex justify-between items-center">
            <a href="/" class="text-2xl font-bold text-gray-800">SNEAKER<span class="text-indigo-600">SHOP</span></a>
            
            <div class="hidden md:flex space-x-8 font-medium">
                <a href="#" class="hover:text-indigo-600 transition">Hombres</a>
                <a href="#" class="hover:text-indigo-600 transition">Mujeres</a>
                <a href="#" class="hover:text-indigo-600 transition">Novedades</a>
                <a href="#" class="hover:text-indigo-600 transition">Ofertas</a>
            </div>

            <div class="flex items-center space-x-5">
                <a href="#" class="text-gray-600 hover:text-indigo-600"><i class="fa-solid fa-magnifying-glass text-xl"></i></a>
                <button @click="isCartOpen = true" class="text-gray-600 hover:text-indigo-600 relative">
                    <i class="fa-solid fa-cart-shopping text-xl"></i>
                    <span x-show="cartCount > 0" x-text="cartCount" class="absolute -top-2 -right-2 bg-indigo-600 text-white text-xs rounded-full h-5 w-5 flex items-center justify-center">0</span>
                </button>
                
                @auth
                    <div class="relative" x-data="{ open: false }">
                        <button @click="open = !open" class="flex items-center space-x-2 text-gray-600 hover:text-indigo-600 focus:outline-none">
                            <i class="fa-solid fa-circle-user text-2xl"></i>
                            <span class="hidden md:block text-sm font-medium">{{ Auth::user()->full_name }}</span>
                            <i class="fa-solid fa-chevron-down text-xs"></i>
                        </button>
                        <div x-show="open" @click.away="open = false" 
                             class="absolute right-0 mt-2 w-48 bg-white rounded-xl shadow-lg border py-2 z-50">
                            <form action="{{ route('logout') }}" method="POST">
                                @csrf
                                <button type="submit" class="w-full text-left px-4 py-2 text-sm text-red-600 hover:bg-red-50 flex items-center">
                                    <i class="fa-solid fa-right-from-bracket mr-2"></i> Cerrar Sesión
                                </button>
                            </form>
                        </div>
                    </div>
                @else
                    <a href="{{ route('login') }}" class="bg-indigo-600 text-white px-6 py-2 rounded-full text-sm font-bold hover:bg-indigo-700 transition">Entrar</a>
                @endauth
            </div>
        </div>
    </nav>

    <!-- Shopping Cart Sidebar -->
    <div x-show="isCartOpen" 
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="translate-x-full"
         x-transition:enter-end="translate-x-0"
         x-transition:leave="transition ease-in duration-300"
         x-transition:leave-start="translate-x-0"
         x-transition:leave-end="translate-x-full"
         class="fixed inset-y-0 right-0 w-full max-w-md bg-white shadow-2xl z-[60] flex flex-col"
         style="display: none;">
        
        <div class="p-6 border-b flex justify-between items-center">
            <h2 class="text-2xl font-bold">Tu Carrito</h2>
            <button @click="isCartOpen = false" class="text-gray-500 hover:text-gray-800">
                <i class="fa-solid fa-xmark text-2xl"></i>
            </button>
        </div>

        <div class="flex-grow overflow-y-auto p-6 space-y-6">
            <template x-if="cart.length === 0">
                <div class="text-center py-12">
                    <i class="fa-solid fa-cart-shopping text-5xl text-gray-200 mb-4"></i>
                    <p class="text-gray-500">Tu carrito está vacío</p>
                </div>
            </template>

            <template x-for="item in cart" :key="item.id">
                <div class="flex items-center space-x-4">
                    <div class="h-20 w-20 bg-gray-100 rounded-lg overflow-hidden flex-shrink-0">
                        <img :src="'https://via.placeholder.com/100x100?text=' + encodeURIComponent(item.name)" :alt="item.name" class="h-full w-full object-cover">
                    </div>
                    <div class="flex-grow">
                        <h4 class="font-bold text-gray-800" x-text="item.name"></h4>
                        <p class="text-sm text-gray-500" x-text="item.brand"></p>
                        <div class="flex justify-between items-center mt-2">
                            <span class="font-bold text-indigo-600" x-text="(item.price * item.quantity).toFixed(2) + '€'"></span>
                            <div class="flex items-center space-x-2">
                                <button @click="if(item.quantity > 1) item.quantity--" class="text-gray-400 hover:text-gray-600"><i class="fa-solid fa-minus"></i></button>
                                <span x-text="item.quantity" class="w-8 text-center font-medium"></span>
                                <button @click="item.quantity++" class="text-gray-400 hover:text-gray-600"><i class="fa-solid fa-plus"></i></button>
                            </div>
                        </div>
                    </div>
                    <button @click="removeFromCart(item.id)" class="text-gray-300 hover:text-red-500 transition">
                        <i class="fa-solid fa-trash-can"></i>
                    </button>
                </div>
            </template>
        </div>

        <div class="p-6 border-t bg-gray-50">
            <div class="flex justify-between items-center mb-6">
                <span class="text-gray-600">Total estimado</span>
                <span class="text-2xl font-bold text-gray-900" x-text="cartTotal.toFixed(2) + '€'"></span>
            </div>
            <button class="w-full bg-indigo-600 text-white py-4 rounded-xl font-bold hover:bg-indigo-700 transition shadow-lg">
                Finalizar Compra
            </button>
        </div>
    </div>

    <!-- Backdrop -->
    <div x-show="isCartOpen" 
         @click="isCartOpen = false"
         x-transition:opacity
         class="fixed inset-0 bg-black/50 z-[55]"
         style="display: none;"></div>

    <!-- Hero Section -->
    <header class="relative bg-gray-900 text-white py-24 overflow-hidden">
        <div class="absolute inset-0 opacity-40">
            <img src="https://images.unsplash.com/photo-1552346154-21d32810aba3?ixlib=rb-4.0.3&auto=format&fit=crop&w=1920&q=80" alt="Hero" class="w-full h-full object-cover">
        </div>
        <div class="container mx-auto px-4 relative z-10 text-center">
            <h1 class="text-5xl md:text-7xl font-bold mb-6">Pisa Fuerte, <br>Vive Mejor</h1>
            <p class="text-xl mb-10 max-w-2xl mx-auto text-gray-200">Descubre la colección más exclusiva de zapatillas urbanas y deportivas. Diseñadas para tu estilo de vida.</p>
            <a href="#catalogo" class="bg-indigo-600 hover:bg-indigo-700 text-white px-10 py-4 rounded-full font-bold text-lg transition shadow-lg inline-block">Ver Catálogo</a>
        </div>
    </header>

    <!-- Features -->
    <section class="py-12 bg-white border-b">
        <div class="container mx-auto px-4 grid grid-cols-1 md:grid-cols-3 gap-8 text-center">
            <div class="p-4">
                <i class="fa-solid fa-truck-fast text-3xl text-indigo-600 mb-4"></i>
                <h3 class="font-bold text-lg mb-2">Envío Gratis</h3>
                <p class="text-gray-600">En pedidos superiores a 100€</p>
            </div>
            <div class="p-4">
                <i class="fa-solid fa-rotate-left text-3xl text-indigo-600 mb-4"></i>
                <h3 class="font-bold text-lg mb-2">Devolución Fácil</h3>
                <p class="text-gray-600">30 días para cambios y devoluciones</p>
            </div>
            <div class="p-4">
                <i class="fa-solid fa-shield-halved text-3xl text-indigo-600 mb-4"></i>
                <h3 class="font-bold text-lg mb-2">Pago Seguro</h3>
                <p class="text-gray-600">100% protección en tus compras</p>
            </div>
        </div>
    </section>

    <div class="container mx-auto px-4 py-12">
        <h1 class="text-3xl font-bold text-gray-800 mb-8">Nuestra Colección</h1>
        
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-8">
            @foreach($products as $product)
            <div class="bg-white rounded-2xl shadow-sm hover:shadow-xl transition duration-300 overflow-hidden group border border-gray-100">
                <div class="relative h-64 bg-gray-100 overflow-hidden">
                    <img src="https://via.placeholder.com/400x400?text={{ urlencode($product->name) }}" 
                         alt="{{ $product->name }}" 
                         class="w-full h-full object-cover group-hover:scale-110 transition duration-500">
                    <div class="absolute top-4 right-4">
                        <span class="bg-white/90 backdrop-blur px-3 py-1 rounded-full text-xs font-bold text-indigo-600 shadow-sm uppercase">
                            {{ $product->category }}
                        </span>
                    </div>
                    <div class="absolute top-4 left-4">
                        <span class="bg-indigo-600 text-white px-3 py-1 rounded-full text-xs font-bold shadow-sm">
                            Stock: {{ $product->stock }}
                        </span>
                    </div>
                </div>
                
                <div class="p-6">
                    <div class="flex justify-between items-start mb-2">
                        <div>
                            <p class="text-xs text-indigo-600 font-bold uppercase tracking-wider mb-1">{{ $product->brand }}</p>
                            <h3 class="text-lg font-bold text-gray-800 line-clamp-1">{{ $product->name }}</h3>
                        </div>
                        <p class="text-xl font-bold text-gray-900">${{ number_format($product->price, 2) }}</p>
                    </div>
                    
                    <p class="text-gray-500 text-sm line-clamp-2 mb-6 h-10">
                        {{ $product->description }}
                    </p>
                    
                    <button @click="addToCart({ 
                        id: {{ $product->id }}, 
                        name: '{{ $product->name }}', 
                        brand: '{{ $product->brand }}', 
                        price: {{ $product->price }} 
                    })" 
                    class="w-full bg-gray-900 text-white py-3 rounded-xl font-bold hover:bg-indigo-600 transition duration-300 flex items-center justify-center space-x-2">
                        <i class="fa-solid fa-cart-plus"></i>
                        <span>Añadir al Carrito</span>
                    </button>
                </div>
            </div>
            @endforeach
        </div>
    </div>

    <!-- Footer -->
    <footer class="bg-gray-900 text-white pt-16 pb-8">
        <div class="container mx-auto px-4 grid grid-cols-1 md:grid-cols-4 gap-12 mb-12">
            <div class="col-span-1 md:col-span-1">
                <a href="/" class="text-2xl font-bold mb-6 block">SNEAKER<span class="text-indigo-600">SHOP</span></a>
                <p class="text-gray-400 mb-6">La mejor tienda online para los amantes de las zapatillas. Calidad, estilo y exclusividad.</p>
                <div class="flex space-x-4">
                    <a href="#" class="text-gray-400 hover:text-white transition"><i class="fa-brands fa-facebook-f text-xl"></i></a>
                    <a href="#" class="text-gray-400 hover:text-white transition"><i class="fa-brands fa-instagram text-xl"></i></a>
                    <a href="#" class="text-gray-400 hover:text-white transition"><i class="fa-brands fa-twitter text-xl"></i></a>
                </div>
            </div>
            <div>
                <h4 class="font-bold text-lg mb-6">Tienda</h4>
                <ul class="space-y-4 text-gray-400">
                    <li><a href="#" class="hover:text-white transition">Hombres</a></li>
                    <li><a href="#" class="hover:text-white transition">Mujeres</a></li>
                    <li><a href="#" class="hover:text-white transition">Niños</a></li>
                    <li><a href="#" class="hover:text-white transition">Accesorios</a></li>
                </ul>
            </div>
            <div>
                <h4 class="font-bold text-lg mb-6">Ayuda</h4>
                <ul class="space-y-4 text-gray-400">
                    <li><a href="#" class="hover:text-white transition">Preguntas Frecuentes</a></li>
                    <li><a href="#" class="hover:text-white transition">Envíos</a></li>
                    <li><a href="#" class="hover:text-white transition">Devoluciones</a></li>
                    <li><a href="#" class="hover:text-white transition">Contacto</a></li>
                </ul>
            </div>
            <div>
                <h4 class="font-bold text-lg mb-6">Newsletter</h4>
                <p class="text-gray-400 mb-4">Suscríbete para recibir ofertas exclusivas.</p>
                <form class="flex flex-col space-y-3">
                    <input type="email" placeholder="Tu email" class="bg-gray-800 border-none rounded-lg px-4 py-3 text-white focus:ring-2 focus:ring-indigo-600 outline-none">
                    <button class="bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-3 rounded-lg transition">Suscribirse</button>
                </form>
            </div>
        </div>
        <div class="container mx-auto px-4 border-t border-gray-800 pt-8 text-center text-gray-500 text-sm">
            <p>&copy; 2026 SneakerShop. Todos los derechos reservados.</p>
        </div>
    </footer>
</body>
</html>