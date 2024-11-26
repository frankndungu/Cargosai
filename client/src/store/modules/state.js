export default {
  cart: [],
  currentPage: 1,
  totalPages: 0,
  totalProducts: 0,
  isModalOpen: false,
  review: {
    name: "",
    rating: 0,
    title: "",
    content: "",
  },
  user: null,
  product: {
    name: "",
    stock: "",
    price: "",
    vendor: "",
    vendorEmail: "",
    vendorLocation: "",
    material: "",
    dimensions: "",
    weight: "",
    description: "",
    images: [], // Store base64 strings for uploaded images
  },
};
