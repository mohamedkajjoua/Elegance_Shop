// Mock product data
export const products = {
    1: {
        id: 1,
        title: 'Line-Pattern Zipper Sweatshirt',
        price: 200,
        image: 'https://images.unsplash.com/photo-1578587018452-892bacefd3f2?w=500',
        badge: 'NEW',
        badgeType: 'new'
    },
    2: {
        id: 2,
        title: 'Black Fantasy Sweatshirt',
        price: 200,
        image: 'https://images.unsplash.com/photo-1556905055-8f358a7a47b2?w=500',
        badge: null,
        badgeType: null
    },
    3: {
        id: 3,
        title: 'Brooklyn-NYC Sweatshirt',
        price: 200,
        originalPrice: 420,
        image: 'https://images.unsplash.com/photo-1620799139507-2a76f79a2f4d?w=500',
        badge: 'BEST SELLER',
        badgeType: 'bestseller'
    },
    4: {
        id: 4,
        title: 'Basic Plain Shirt',
        price: 200,
        originalPrice: 420,
        image: 'https://images.unsplash.com/photo-1583743814966-8936f5b7be1a?w=500',
        badge: 'HOT PROMO',
        badgeType: 'promo'
    },
    5: {
        id: 5,
        title: 'Basic Orange Sweatshirt',
        price: 200,
        image: 'https://images.unsplash.com/photo-1620799140408-ed5341cd2431?w=500',
        badge: 'NEW',
        badgeType: 'new'
    },
    6: {
        id: 6,
        title: 'Alui Sweatshit X Alvent G',
        price: 200,
        image: 'https://images.unsplash.com/photo-1618354691373-d851c5c3a990?w=500',
        badge: null,
        badgeType: null
    },
    7: {
        id: 7,
        title: 'Flowers Printed Sweatshirt',
        price: 200,
        originalPrice: 420,
        image: 'https://images.unsplash.com/photo-1576566588028-4147f3842f27?w=500',
        badge: 'HOT PROMO',
        badgeType: 'promo'
    },
    8: {
        id: 8,
        title: 'Relaxed Fit Printed Sweatshirt',
        price: 200,
        image: 'https://images.unsplash.com/photo-1591047139829-d91aecb6caea?w=500',
        badge: 'NEW',
        badgeType: 'new'
    },
    9: {
        id: 9,
        title: 'Letter Pattern Knitted Vest',
        price: 200,
        image: 'https://images.unsplash.com/photo-1556905055-8f358a7a47b2?w=500',
        badge: null,
        badgeType: null
    }
}

export const categories = [
    { id: 1, name: 'T-Shirt', slug: 't-shirt' },
    { id: 2, name: 'Sweatshirt', slug: 'sweatshirt', active: true },
    { id: 3, name: 'Dress', slug: 'dress' },
    { id: 4, name: 'Pants and Skirt', slug: 'pants-skirt' },
    { id: 5, name: 'Swimsuit', slug: 'swimsuit' },
    { id: 6, name: 'Stuff and Accessories', slug: 'accessories' }
]

export const types = [
    { id: 1, name: 'Basic' },
    { id: 2, name: 'Pattern' },
    { id: 3, name: 'Hoodie' },
    { id: 4, name: 'Zipper' },
    { id: 5, name: 'Oversize', checked: true }
]

export const colors = [
    { id: 1, name: 'Black', hex: '#1A1A2E' },
    { id: 2, name: 'Red', hex: '#EF4444' },
    { id: 3, name: 'Brown', hex: '#92400E' },
    { id: 4, name: 'Multicolour', hex: 'linear-gradient(135deg, #FF6B6B, #4ECDC4, #FFE66D)', checked: true },
    { id: 5, name: 'Grey', hex: '#9CA3AF' },
    { id: 6, name: 'Blue', hex: '#3B82F6' }
]

export const sizes = ['S', 'M', 'L', 'XL', 'XXL']

export function getProductById(id) {
    return products[id] || null
}

export function getProductsList() {
    return Object.values(products)
}
