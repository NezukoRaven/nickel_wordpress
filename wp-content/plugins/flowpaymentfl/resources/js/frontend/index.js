import React from 'react';
import { registerPaymentMethod } from '@woocommerce/blocks-registry';
import { getSetting } from '@woocommerce/settings';
import { __ } from '@wordpress/i18n';
import { decodeEntities } from '@wordpress/html-entities';
import { PAYMENT_METHOD_NAME } from './constants';

const flowPaymentDefaultTitle = __("Flow", "woocommerce-gateway-flowpayment");
const decodeTitle = (title) => decodeEntities(title) || flowPaymentDefaultTitle;
const decodeDescription = (description) => decodeEntities(description || "");

const flowPaymentSettings = getSetting("flowpayment_data", {});
const flowPaymentTitle = decodeTitle(flowPaymentSettings.title);

function handleErrorMessage(divNotices) {
    const params = new URLSearchParams(window.location.search);
    const cancel_order = params.get('cancel_order');
    const error_message = params.get('error_message');

    if (divNotices && cancel_order === "true" && error_message) {

        const divErrorCheckout = document.createElement('div');
        divErrorCheckout.className = 'wc-block-components-notice-banner is-error';
        divErrorCheckout.innerHTML =
            '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24" height="24" aria-hidden="true" focusable="false">' +
            '<path d="M12 3.2c-4.8 0-8.8 3.9-8.8 8.8 0 4.8 3.9 8.8 8.8 8.8 4.8 0 8.8-3.9 8.8-8.8 0-4.8-4-8.8-8.8-8.8zm0 16c-4 0-7.2-3.3-7.2-7.2C4.8 8 8 4.8 12 4.8s7.2 3.3 7.2 7.2c0 4-3.2 7.2-7.2 7.2zM11 17h2v-6h-2v6zm0-8h2V7h-2v2z">' +
            '</path>' +
            '</svg>' +
            '<div class="wc-block-components-notice-banner__content">' +
            error_message +
            '</div>';

        divNotices.appendChild(divErrorCheckout);
    }
}

document.addEventListener('DOMContentLoaded', function() {
    const targetNode = document.body;

    if (targetNode) {
        const config = { childList: true, subtree: true };
        const callback = (mutationsList, observer) => {
            for (let mutation of mutationsList) {
                if (mutation.type === 'childList') {
                    const divNotices = document.querySelector('#payment-method .wc-block-components-checkout-step__content .wc-block-components-notices');
                    if (divNotices) {
                        observer.disconnect();
                        handleErrorMessage(divNotices);
                        break;
                    }
                }
            }
        };
        const observer = new MutationObserver(callback);
        observer.observe(targetNode, config);
    }
});

const labelElement = React.createElement("div", {
    style: {
        display: "flex",
        flexDirection: "row",
        gap: "0.5rem",
        alignItems: "center"
    }
},
    React.createElement("img", { 
            src: flowPaymentSettings.logo_url, 
            alt: flowPaymentTitle,
            style: { height: '36px', maxHeight: '36px' }  }),
    React.createElement("div", null, flowPaymentTitle)
);

const Flowpayment_Gateway = {
    name: PAYMENT_METHOD_NAME,
    label: labelElement,
    content: React.createElement("div", null, decodeDescription(flowPaymentSettings.description)),
    edit: React.createElement("div", null, decodeDescription(flowPaymentSettings.description)),
    canMakePayment: () => true,
    ariaLabel: flowPaymentTitle
};

registerPaymentMethod(Flowpayment_Gateway);