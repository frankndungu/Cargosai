<template>
  <div class="product-information-section">
    <section class="info-section">
      <h2 class="section-title">International Delivery</h2>
      <div class="section-content">
        <p>
          We deliver worldwide, postage prices are based on the total weight of
          your order, calculated at the checkout. Most services are sent Tracked
          & Signed unless otherwise stated.
        </p>
        <p>
          Please note, most countries will charge import duty on goods over a
          certain value, it is the buyer's responsibility to pay any charges
          that occur on importation at destination.
        </p>
      </div>
    </section>

    <section class="info-section">
      <h2 class="section-title">Returns</h2>
      <div class="section-content">
        <p>
          If you decide you don't like your product we will be happy to accept
          returns, and refund you for the item as long as it is returned to us
          within 21 days of purchase. Returned products must be in the same
          condition as was received, complete with original swing tags and
          labels attached. Where possible, please include a note or brief reason
          for return.
        </p>
        <p>
          Unfortunately we can't refund the cost of shipping and we do require
          you to pay for the postage of your returned items. Used or customer
          damaged items cannot be refunded or exchanged. We advise you to send
          this as recorded post, or keep a copy of proof of postage in the event
          that the package goes missing.
        </p>
        <p>
          If your product arrives with you damaged from the post on it's way out
          to you, and you would like to return it, we will of course be happy to
          reimburse for the item and the cost of shipping as long as you let us
          know about the damaged product within 1 week of receiving your items.
        </p>
      </div>
    </section>

    <section class="info-section">
      <h2 class="section-title">Cancellation of an Order</h2>
      <div class="section-content">
        <p>
          You have the right to cancel your order, however if the order has
          already been processed and shipped by Maasai Market Online, you will
          need to follow the standard returns procedure. Please let us know as
          soon as possible.
        </p>
      </div>
    </section>

    <section class="info-section">
      <h2 class="section-title">Handmade Disclaimer</h2>
      <div class="section-content">
        <p>
          All of our products are exquisitely handmade in remote rural corners
          of Kenya using natural materials, one piece at a time, with no two
          products the same. Although every effort is made to create only the
          best product, there may be natural blemishes or bumps and curves in
          places, as well as patches of dirt that can be sometimes noticed on
          the recycled wool sisal mix baskets. At Maasai Market Online, we take
          pride in showcasing these unique characteristics as they represent the
          authentic craftsmanship of our skilled artisans. Each piece tells a
          story of traditional techniques passed down through generations,
          supporting local communities while preserving Kenya's rich cultural
          heritage. These slight variations and natural markings are not
          defects, but rather a testament to the genuine, handcrafted nature of
          our products and the sustainable practices we champion.
        </p>
      </div>
    </section>
  </div>
</template>

<style>
.product-information-section {
  padding: 30px 0;
}

.info-section {
  margin-bottom: 30px;
}

.section-title {
  font-size: 1.2rem;
  font-weight: var(--font-bold);
  color: var(--dark-color);
  margin-bottom: 15px;
}

.section-content {
  color: var(--text-color);
  line-height: 1.6;
}

.section-content p {
  margin-bottom: 15px;
}

.section-content p:last-child {
  margin-bottom: 0;
}

@media (max-width: 768px) {
  .product-information-section {
    padding: 20px 0;
  }

  .section-title {
    font-size: 1.1rem;
  }
}
</style>
