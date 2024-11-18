import { RemoveProduct } from "../quote_list/quote_items";
import { decode } from "html-entities";
import { RenderDelete } from "../quote_list/quote_list_content";
import { PriceWithCurrency } from "../quote_list/priceWithCurrency";

export const MiniQuoteContents = (props) => {
  return (
    <div className="d-flex flex-column gap-1 mb-3">
      <div className="d-flex gap-2 position-relative miniquote-content align-items-center">
        <RenderDelete props={props} />
        <div className="elex-raq-icon">
          <div className="ratio ratio-4x3">
            <img src={props.item.image_url} alt="" />
          </div>
        </div>
        <div>
          <h6 className=" raq-fs">
            <small>{props.item.title}</small>
          </h6>
          <div className="text-secondary raq-fxs">
            {props.show_quantity ? props.item.quantity : ""}
            {false === props.hide_price_global &&
            props.show_price &&
            props.show_quantity
              ? " X "
              : ""}
              {props.show_price && false === props.hide_price_global
              ? <PriceWithCurrency currency={props.currency} price={props.item.item_cost} currency_position={props.currency_position}/>
              : ""}
              {" "}
          </div>
        </div>
      </div>
    </div>
  );
};
