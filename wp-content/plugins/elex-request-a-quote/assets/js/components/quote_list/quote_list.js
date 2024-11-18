import { MaximizePopUp } from "./maximize";
import { MinimizePopUp } from "./minimize";

import { IsQuoteEmpty } from "./quote_list_empty";
import { QuoteListProducts } from "./quote_list_content";
import { useEffect, useState } from "react-wordpress";
import { UpdateQuoteMsg } from "../toast_message/update_message";
import { ClearQuoteMsg } from "../toast_message/update_message";
import { QuoteForm } from "../form/fields/form";

const ClosePopUp = () => {
  window.location = localStorage.getItem('redirect_page');
  jQuery(".elex-raq-quote-list-popup-container").addClass("d-none");
};

const OpenQuoteList = () => {
  jQuery(".elex-raq-quote-list-popup-container").removeClass("d-none");
};

export const QuoteListpage = (props) => {

  const IsQuoteForm = ({ data, settings, items, default_image ,color }) => {
    let enabled = true === data || "true" === data ? true : false;
    if(items.items.length === 0 && (settings.quote_request_form !== 'true' || settings.quote_request_form !== true) ){
      return null;
  }

    if (true === enabled) {
      return <QuoteForm color={color} default_image={default_image} form={FormData} />;
    }
    return null;
  };

  wp.element.useEffect(() => {
    jQuery(window).on("update_quantity_event", function (e, quotelist_data) {
      setQuoteItems(quotelist_data);
    });
    jQuery(window).on("clear_list_event", function (e, quotelist_data) {
      setQuoteItems(quotelist_data);
    });
    jQuery(window).on("delete_event", function (e, quotelist_data) {
      setQuoteItems(quotelist_data);
    });
  }, []);
  const [quoteItems, setQuoteItems] = wp.element.useState(props.quote_items);
  const shop_page = wp.element.useState(
    props.quote_items.additional_settings.add_more_items_button_redirection
  );
  const empty_image = wp.element.useState(props.quote_items.empty_image);
  const default_image = wp.element.useState(props.quote_items.default_image);

  const dynamicClass = (quoteItems.settings.layout.product_on_top_form_on_bottom === true) ? "flex-column d-lg-flex gap-3 " : "d-lg-flex gap-3 ";

      const addWidthForHLayout = (quoteItems.settings.layout.product_on_top_form_on_bottom === false)  ? "flex-fill elex-raq-quote-details-container   mb-5 mb-md-0 position-relative flex-column d-lg-flex" : "flex-fill mb-5 mb-md-0 position-relative flex-column d-lg-flex";
      const popupClasses = (quoteItems.settings.layout.product_on_top_form_on_bottom === false) ? "flex-fill  mb-5 mb-md-0 position-relative p-sm-4 p-2 elex-raq-quote-list-popup-content d-lg-flex" : "flex-fill  mb-5 mb-md-0 position-relative p-sm-4 p-2 elex-raq-quote-list-popup-content ";

  const [FormData, SetForm] = wp.element.useState(
    props.quote_items.form_settings
  );
const button_color = wp.element.useState(props.quote_items.general.general.button_default_color);


  if (props.quote_items.general.general.open_quote_form === "new_page") {
    return (
      <>
        <div>
          <div className={dynamicClass}>
            <div className={addWidthForHLayout}>
              <IsQuoteEmpty
                color={button_color} 
                empty_image_url={empty_image}
                shop_page={
                  props.quote_items.additional_settings
                    .add_more_items_button_redirection
                }
                data={quoteItems.quote_list}
                settings={quoteItems.settings.show_if_list_empty}
              />

              <QuoteListProducts data={props} />
            </div>
            <IsQuoteForm
              color={button_color} 
              default_image={default_image}
              items={quoteItems.quote_list}
              settings={quoteItems.settings.show_if_list_empty}
              data={props.quote_items.form_settings.show_form}
            />
          </div>
        </div>
      </>
    );
  }

  return (
    <>
      <div className=" bg-secondary bg-opacity-25 elex-raq-quote-list-popup-container">
        <div className=" bg-white d-flex flex-column elex-raq-quote-list-popup elex-raq-minimize">
          <div className="position-relative m-4">
            <h4 className="fw-bold text-center mb-0">
              {quoteItems.settings.title}
            </h4>
            <button
              onClick={ClosePopUp}
              className=" btn btn-sm rounded-circle position-absolute end-0 top-50 translate-middle-y elex-raq-view-quote-list-close-btn"
            >
              <svg
                xmlns="http://www.w3.org/2000/svg"
                width="9.313"
                height="9.313"
                viewBox="0 0 9.313 9.313"
              >
                <path
                  id="Icon_ionic-md-close"
                  data-name="Icon ionic-md-close"
                  d="M12.656,4.275l-.931-.931L8,7.069,4.275,3.344l-.931.931L7.069,8,3.344,11.725l.931.931L8,8.931l3.725,3.725.931-.931L8.931,8Z"
                  transform="translate(-3.344 -3.344)"
                />
              </svg>
            </button>
          </div>

          <div className={popupClasses}>
            <IsQuoteEmpty
            color={button_color} 
              empty_image_url={empty_image}
              shop_page={
                props.quote_items.additional_settings
                  .add_more_items_button_redirection
              }
              data={quoteItems.quote_list}
              settings={quoteItems.settings.show_if_list_empty}
            />

            <QuoteListProducts data={props} />

            <IsQuoteForm
              color={button_color}
              default_image={default_image}
              items={quoteItems.quote_list}
              settings={quoteItems.settings.show_if_list_empty}
              data={props.quote_items.form_settings.show_form}
            />
          </div>

          <div className="position-absolute start-50  d-none translate-middle-x text-center bg-transparent elex-raq-max-min">
            <button
              onClick={MinimizePopUp}
              className="d-none lh-1 raq-fxs   btn text-primary  bg-white   elex-raq-minimize-btn"
            >
              <i className="fa-solid fa-compress fs-6 me-2"></i>Minimize
            </button>

            <button
              onClick={MaximizePopUp}
              className="raq-fxs lh-1  btn text-primary  bg-white   elex-raq-maximize-btn"
            >
              <i className="fa-solid fa-expand fs-6"></i>
              <span className="ms-2">Maximize</span>
            </button>
          </div>
        </div>
      </div>
    </>
  );
};

jQuery(document).ready(function () {
  jQuery.ajax({
    type: "post",
    url: quote_list_ajax_obj.ajax_url,
    data: {
      action: "get_the_quote_list",
      ajax_raq_nonce: jQuery("#ajax_raq_nonce").val(),
    },
    success: function (data) {
      if (document.getElementById("quote_list") !== null) {
        wp.element.render(
          <QuoteListpage quote_items={data.data} key={data.data.id} />,
          document.getElementById("quote_list")
        );
      }
    },
  });
});
