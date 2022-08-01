var stripe = Stripe("pk_test_3SAQzHl0QCEJX548Fgp4xUTA00uOhu03j7");
// Create an instance of Elements
var elements = stripe.elements();
var style = {
  base: {
    color: "#32325d",
    lineHeight: "24px",
    fontFamily: '"Helvetica Neue", Helvetica, sans-serif',
    fontSmoothing: "antialiased",
    fontSize: "20px",
    "::placeholder": {
      color: "#aab7c4",
    },
  },
  invalid: {
    color: "#fa755a",
    iconColor: "#fa755a",
  },
};

var card = elements.create("card", { style: style });
const displayError = document.getElementById("card-errors");
card.mount("#card-element");
card.addEventListener("change", ({ error }) => {
  if (error) {
    displayError.textContent = error.message;
    displayError.style.color = "red";
  } else {
    displayError.textContent = "";
  }
});

var submitButton = document.getElementById("submit");

submitButton.addEventListener("click", function (ev) {
  ev.preventDefault();
  submitButton.disable = true;
  stripe
    .confirmCardPayment("{{ clientSecret }}", {
      payment_method: {
        card: card,
      },
    })
    .then(function (result) {
      if (result.error) {
        // Show error to your customer (e.g., insufficient funds)
        submitButton.disable = false;
        displayError.textContent = result.error.message;
        console.log(result.error.message);
      } else {
        // The payment has been processed!
        if (result.paymentIntent.status === "succeeded") {
          var paymentIntent = result.paymentIntent;
          // var token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
          var form = document.getElementById("payment-form");
          var url = form.action;
          var redirect = "/merci";
          fetch(url, {
            headers: {
              "content-type": "application/json",
              accept: "application/json, text-plain, */*",
              "X-requested-with": "XMLHttpRequest",
            },
            method: "post",
            body: JSON.stringify({ paymentIntent: paymentIntent }),
          })
            .then((data) => {
              console.log(data);
              form.reset();
              //window.location.href = redirect;
            })
            .catch((error) => {
              displayError.textContent = error;
              //window.location.href = "/desole";
            });
        }
      }
    });
});
