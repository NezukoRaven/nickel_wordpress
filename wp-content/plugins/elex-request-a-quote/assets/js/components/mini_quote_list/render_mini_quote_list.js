// const { MiniQuoteContents } = require("./mini_quote_contents");

import { MiniQuoteContents } from "./mini_quote_contents";

import { useEffect, useState } from "react-wordpress";
import __ from '../translate';


const MiniQuote = (props) => {
  const onQuoteListItemDelete = (i) => {
    const newQuoteList = { ...miniquoteItems };

    newQuoteList.quote_list.items.splice(i, 1);
    setQuoteItems(newQuoteList);
  };

  const ShowButtonLabel = ({ label, show_label }) => {
    if (false === show_label) {
      return "";
    }
    return <small>{label}</small>;
  };

  const ShowListOnHover = (props) => {
    if (false === widget.show_list_popup_on_hover) {
      return "";
    }
    return (
      <div className="bg-white shadow-sm position-absolute  start-50 translate-middle-x  p-2 rounded-3 elex-raq-view-quote-dropdown">
        <h6 className="text-nowrap text-center">
          {props.count}{' '}{ __('items in Quote List','elex-request-a-quote')}
        </h6>
        {miniquoteItems.quote_list.items.map((item, i) => (
          <MiniQuoteContents
            onDelete={() => onQuoteListItemDelete(i)}
            show_quantity={
              miniquoteItems.settings.show_on_product_table.quantity
            }
            show_price={
              miniquoteItems.settings.show_on_product_table.product_price
            }
            hide_price_global={
              miniquoteItems.general.hide_add_to_cart.hide_price
            }
            currency={miniquoteItems.quote_list.wc_currency}
            currency_position={miniquoteItems.quote_list.currency_position}

            item={item}
            key={i}
          />
        ))}
        <a href={props.path}>
          <button 
          style={{
            backgroundColor: props.button_color[0]
          }} 
          className="elex-raq-view-quote-list-open-btn btn btn-sm btn-primary w-100">
            { __('View Quote List','elex-request-a-quote')}
          </button>
        </a>
      </div>
    );
  };

  const [miniquoteItems, setQuoteItems] = wp.element.useState(
    props.quote_items
  );
  const button_color = wp.element.useState(props.quote_items.general.general.button_default_color);
  let count = 0;
  for (const item of miniquoteItems.quote_list.items) {
    count += parseInt(item.quantity);
  }
  wp.element.useEffect(() => {
    jQuery(window).on("add_to_quote_event", function (e, quotelist_data) {
      setQuoteItems(quotelist_data);
    });

    jQuery(window).on("clear_list_event", function (e, quotelist_data) {
      setQuoteItems(quotelist_data);
    });
    jQuery(window).on("update_quantity_event", function (e, quotelist_data) {
      setQuoteItems(quotelist_data);
    });
  }, [miniquoteItems]);
  const [widget] = wp.element.useState(props.widgetSettings);

  if (widget.show_widget_icon === false) {
    return null;
  }

  const ChangeIconColor = (e) => {
    e.target.style.fill = widget.widget_color;
  };
  const ChangeColorToWhite = (e) => {
    e.target.style.fill = "";
  };
  const [backgroundColor, setBackgroundColor] = wp.element.useState("");

  return (
    <div className="container">
      <div className="d-flex gap-3 align-items-center m-0 py-2">
        <div className="flex-fill">
          <div className="d-flex justify-content-end elex-rqst-float-minicart-icon-alignment">
            <div className="nav position-relative">
              <li className="nav-item p-0 d-flex align-items-center gap-1 elex-raq-view-quote-dropdown-btn position-relative ">
                <a href={miniquoteItems.settings.selected_page}>
                  <button
                    onMouseOver={ChangeIconColor}
                    onMouseOut={ChangeColorToWhite}
                    className="btn btn-sm p-0 shadow-none position-relative"
                  >
                    <svg
                      id="quote_list_svg"
                      xmlns="http://www.w3.org/2000/svg"
                      width="30"
                      height="26"
                      viewBox="0 0 36.01 31.5"
                    >
                      <g
                        id="Quote_list_icon"
                        data-name="Quote list icon"
                        transform="translate(0 -32)"
                      >
                        <path
                          id="Path_466"
                          data-name="Path 466"
                          d="M32.663,32H8.985a3.351,3.351,0,0,0-3.347,3.347V56.805H.529A.529.529,0,0,0,0,57.334v2.819A3.351,3.351,0,0,0,3.347,63.5H27.025a3.351,3.351,0,0,0,3.347-3.347V38.695h5.109a.529.529,0,0,0,.529-.529V35.347A3.351,3.351,0,0,0,32.663,32ZM3.347,62.443a2.293,2.293,0,0,1-2.29-2.29v-2.29H23.678v2.29a3.335,3.335,0,0,0,.908,2.29Zm25.968-2.29a2.29,2.29,0,0,1-4.581,0V57.334a.529.529,0,0,0-.529-.529H6.695V35.347a2.293,2.293,0,0,1,2.29-2.29h21.24a3.334,3.334,0,0,0-.909,2.29V60.153Zm5.638-22.515H30.372v-2.29a2.29,2.29,0,0,1,4.581,0Z"
                        />
                        <path
                          id="Path_467"
                          data-name="Path 467"
                          d="M137.656,104h-1.128a.529.529,0,0,0,0,1.057h1.128a.529.529,0,0,0,0-1.057Z"
                          transform="translate(-126.416 -66.926)"
                        />
                        <path
                          id="Path_468"
                          data-name="Path 468"
                          d="M196.931,104h-12.4a.529.529,0,0,0,0,1.057h12.4a.529.529,0,0,0,0-1.057Z"
                          transform="translate(-171.034 -66.926)"
                        />
                        <path
                          id="Path_469"
                          data-name="Path 469"
                          d="M137.656,144h-1.128a.529.529,0,1,0,0,1.057h1.128a.529.529,0,0,0,0-1.057Z"
                          transform="translate(-126.416 -104.107)"
                        />
                        <path
                          id="Path_470"
                          data-name="Path 470"
                          d="M184.529,145.057h10.148a.529.529,0,0,0,0-1.057H184.529a.529.529,0,0,0,0,1.057Z"
                          transform="translate(-171.034 -104.107)"
                        />
                        <path
                          id="Path_471"
                          data-name="Path 471"
                          d="M137.656,184h-1.128a.529.529,0,0,0,0,1.057h1.128a.529.529,0,0,0,0-1.057Z"
                          transform="translate(-126.416 -141.289)"
                        />
                        <path
                          id="Path_472"
                          data-name="Path 472"
                          d="M196.931,184h-12.4a.529.529,0,0,0,0,1.057h12.4a.529.529,0,0,0,0-1.057Z"
                          transform="translate(-171.034 -141.289)"
                        />
                        <path
                          id="Path_473"
                          data-name="Path 473"
                          d="M137.656,224h-1.128a.529.529,0,1,0,0,1.057h1.128a.529.529,0,1,0,0-1.057Z"
                          transform="translate(-126.416 -178.47)"
                        />
                        <path
                          id="Path_474"
                          data-name="Path 474"
                          d="M184.529,225.057H195.8a.529.529,0,1,0,0-1.057H184.529a.529.529,0,1,0,0,1.057Z"
                          transform="translate(-171.034 -178.47)"
                        />
                        <path
                          id="Path_475"
                          data-name="Path 475"
                          d="M137.656,264h-1.128a.529.529,0,1,0,0,1.057h1.128a.529.529,0,1,0,0-1.057Z"
                          transform="translate(-126.416 -215.651)"
                        />
                        <path
                          id="Path_476"
                          data-name="Path 476"
                          d="M184.529,265.057h9.02a.529.529,0,1,0,0-1.057h-9.02a.529.529,0,1,0,0,1.057Z"
                          transform="translate(-171.034 -215.651)"
                        />
                        <path
                          id="Path_477"
                          data-name="Path 477"
                          d="M137.656,304h-1.128a.529.529,0,0,0,0,1.057h1.128a.529.529,0,0,0,0-1.057Z"
                          transform="translate(-126.416 -252.832)"
                        />
                        <path
                          id="Path_478"
                          data-name="Path 478"
                          d="M196.931,304h-12.4a.529.529,0,0,0,0,1.057h12.4a.529.529,0,0,0,0-1.057Z"
                          transform="translate(-171.034 -252.832)"
                        />
                        <path
                          id="Path_479"
                          data-name="Path 479"
                          d="M137.656,344h-1.128a.529.529,0,1,0,0,1.057h1.128a.529.529,0,1,0,0-1.057Z"
                          transform="translate(-126.416 -290.013)"
                        />
                        <path
                          id="Path_480"
                          data-name="Path 480"
                          d="M195.24,344H184.529a.529.529,0,1,0,0,1.057H195.24a.529.529,0,1,0,0-1.057Z"
                          transform="translate(-171.034 -290.013)"
                        />
                      </g>
                    </svg>
                    <span 
                     style={{
                      backgroundColor: button_color[0]
                    }} 
                    className="position-absolute top-0 start-100 translate-middle badge rounded-pill">
                      {count}
                    </span>
                  </button>
                </a>

                <ShowButtonLabel
                  label={widget.button_label}
                  show_label={widget.show_button_label}
                />
                <ShowListOnHover
                button_color={button_color} 
                  count={count}
                  color={widget.widget_color}
                  account_url={miniquoteItems.page_url}
                  disable_for_guest={
                    miniquoteItems.general.general.disable_quote_for_guest
                  }
                  path={miniquoteItems.page_url}
                />
              </li>
            </div>
          </div>
        </div>
      </div>
    </div>
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
      const iconPosition = data?.widget?.quote_list_icon_position
        ? data.widget.quote_list_icon_position
        : "float";
        if( true === data.data.widget.show_widget_icon ){
          wp.element.render(
              <MiniQuote
                widgetSettings={data.data.widget}
                quote_items={data.data}
                key={data.data.id}
                iconPosition={iconPosition}
              />,
              document.getElementById("mini_quote_list")
            );
       }
    },
  });
});
