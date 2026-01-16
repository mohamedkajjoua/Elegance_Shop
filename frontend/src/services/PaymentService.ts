import api from "./api";

class PaymentService {
  async createPaymentIntent(orderId: any) {
    return await api.post("/payment/intent", {
      order_id: orderId,
    });
  }
}

export default new PaymentService();
