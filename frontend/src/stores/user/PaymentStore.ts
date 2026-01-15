import PaymentService from "@/services/PaymentService";
import { defineStore } from "pinia";
import { ref } from "vue";

export const usePaymentStore = defineStore("payment", () => {
  const clientSecret = ref(null);
  const isLoading = ref(false);
  const error = ref();
  const paymentStatus = ref();

  const initializePayment = async (orderId: any) => {
    const res = await PaymentService.createPaymentIntent(orderId);
    clientSecret.value = res.data.client_secret || res.data.clientSecret;

    try {
      if (!clientSecret.value) {
        throw new Error("Received empty client secret");
      }
      return clientSecret.value;
    } catch (err: any) {
      error.value = err.response?.data?.message || "Failed to initialize payment";
      throw err;
    }
  };
  /**
   * 2. تأكيد الدفع مع Stripe
   */
  const confirmPayment = async (stripe: any, elements: any, cardHolderName: any) => {
    // التحقق من التهيئة
    if (!stripe || !elements) {
      error.value = "Stripe is not initialized.";
      return;
    }

    isLoading.value = true;
    error.value = null;
    paymentStatus.value = "processing";

    try {
      // ✅ التصحيح: بناء الرابط يدوياً للتأكد من صحته
      const returnUrl = window.location.origin + "/success";

      // محاولة الدفع
      const { error: stripeError } = await stripe.confirmPayment({
        elements, // العناصر (رقم البطاقة، إلخ)
        confirmParams: {
          return_url: returnUrl, // ⬅️ هذا هو السطر المفقود أو المكتوب خطأ سابقاً
          payment_method_data: {
            billing_details: {
              name: cardHolderName, // اسم صاحب البطاقة
            },
          },
        },
      });

      // ⚠️ ملاحظة: إذا نجح الدفع، سيقوم Stripe بإعادة توجيه الصفحة فوراً إلى return_url
      // الكود أدناه لن يعمل إلا إذا حدث خطأ منع إعادة التوجيه (مثل رفض البطاقة)

      if (stripeError) {
        if (stripeError.type === "card_error" || stripeError.type === "validation_error") {
          error.value = stripeError.message;
        } else {
          error.value = "An unexpected error occurred.";
        }
        paymentStatus.value = "failed";
      }
    } catch (err) {
      console.error("Confirm Payment Error:", err);
      error.value = "System error during payment.";
      paymentStatus.value = "failed";
    } finally {
      isLoading.value = false;
    }
  };
  const restPaymentState = () => {
    clientSecret.value = null;
    isLoading.value = false;
    error.value = null;
    paymentStatus.value = null;
  };

  return {
    clientSecret,
    isLoading,
    error,
    paymentStatus,

    initializePayment,
    confirmPayment,
    restPaymentState,
  };
});
