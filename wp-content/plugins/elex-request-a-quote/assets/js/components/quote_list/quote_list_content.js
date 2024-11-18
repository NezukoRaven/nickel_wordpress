import {HideOrShowAddMoreButton} from './show_add_more_button';
import {HideOrShowClearList} from './show_clear_list';
import {PriceWithCurrency} from './priceWithCurrency'

import {RemoveProduct} from './quote_items';

import {decode} from 'html-entities';

import __ from '../translate';

let tempElexRqQuoteList = []

export const RenderDelete = (data) => {

 

 if(data.props.item.type === 'composite' && data.props.item.child === true){
 return null;
 }
 
 return(
 <button onClick={(e) => {e.preventDefault(); RemoveProduct(data.props.item.product_id, data.props.item.variation_id, data.props.onDelete);}} className="remove_product btn btn-sm rounded-circle p-1 lh-1">
 <svg xmlns="http://www.w3.org/2000/svg" width="15" height="16.5"
 viewBox="0 0 15 16.5">
 <g id="Icon_feather-trash-2" data-name="Icon feather-trash-2"
 transform="translate(0.75 0.75)">
 <path data-name="Path 482" d="M2.25,4.5h13.5"
 transform="translate(-2.25 -1.5)" fill="none" stroke="#000"
 stroke-linecap="round" stroke-linejoin="round"
 stroke-width="1.5" />
 <path data-name="Path 483"
 d="M14.25,4.5V15a1.5,1.5,0,0,1-1.5,1.5H5.25A1.5,1.5,0,0,1,3.75,15V4.5M6,4.5V3A1.5,1.5,0,0,1,7.5,1.5h3A1.5,1.5,0,0,1,12,3V4.5"
 transform="translate(-2.25 -1.5)" fill="none" stroke="#000"
 stroke-linecap="round" stroke-linejoin="round"
 stroke-width="1.5" />
 <path data-name="Path 484" d="M7.5,8.25v4.5"
 transform="translate(-2.25 -1.5)" fill="none" stroke="#000"
 stroke-linecap="round" stroke-linejoin="round"
 stroke-width="1.5" />
 <path data-name="Path 485" d="M10.5,8.25v4.5"
 transform="translate(-2.25 -1.5)" fill="none" stroke="#000"
 stroke-linecap="round" stroke-linejoin="round"
 stroke-width="1.5" />
 </g>
 </svg>
 </button>
 )
}
const ShowProductPrice = (props) => {
 if(props.show_price === true && props.is_hide_price !== true ){
 return( <div className="col-2 elex-raq-quote-hideable-header d-md-block d-none">
 <h6 className="mb-0">{__('Price','elex-request-a-quote')}</h6>
 </div>)
 }
 return( <div className="col-2 elex-raq-quote-hideable-header d-md-block d-none">
 
</div>)
 // return null;
}

const ShowProductQuantity = (props) => {
 if(props.show_quantity === true ){
 return( <div className="col-2 elex-raq-quote-hideable-header d-md-block d-none">
 <h6 className="mb-0">{ __('Quantity','elex-request-a-quote')}</h6>
</div>
);
 }
 return( <div className="col-2 elex-raq-quote-hideable-header d-md-block d-none">
 
</div>)
}

const ShowProductSubtotal = (props) => {


 if(props.show_subtotal === true && props.is_hide_price !== true ){
 return( <div className="col-2 elex-raq-quote-hideable-header text-end d-md-block d-none">
 <h6 className="mb-0">{__('Subtotal','elex-request-a-quote')}</h6>
 </div>);
 }
 return( <div className="col-2 elex-raq-quote-hideable-header d-md-block d-none">
 
</div>)
}

const ShowTax = (props) => {
 if(props.show_tax === true && props.is_hide_price !== true ){
 return(
 <div className="d-flex justify-content-between mb-2">
 <h6 className="mb-0">{__('Tax','elex-request-a-quote')}</h6>

 <div className="raq-fxs">
    {
        props.tax_value !== ''?<PriceWithCurrency currency={props.currency} price={props.tax_value} currency_position={props.currency_position}/>: ''
    } 
 
 </div>
 </div>
 )
 }
 return null;

}

const ShowSubTotal = (props) => {

 if(props.show_sub_total === true && props.is_hide_price !== true ){
 return(
 <div className="d-flex justify-content-between mb-2">
 <h6 className="mb-0">{__('Subtotal','elex-request-a-quote')}</h6>

 <div className="raq-fxs">
    {
        props.sub_total !== ''?<PriceWithCurrency currency={props.currency} price={props.sub_total} currency_position={props.currency_position}/>: ''
    } 
 
 </div>
 </div>
 )
 }
 return null;

}
const ShowTotal = (props) => {

 if(props.show_sub_total === true && props.is_hide_price !== true ){
 return(
 <div className="d-flex justify-content-between mb-2">
 <h6 className="mb-0">{__('Total','elex-request-a-quote')}</h6>

 <div className="raq-fxs">
    {
        props.total !== ''?<PriceWithCurrency currency={props.currency} price={props.total} currency_position={props.currency_position}/>: ''
    } 
    
    </div>

 
 </div>
 )
 }
 return null;
}
const ShowSKU = (props) => {
 if(props.is_sku_checked === true){
 return(
 <div className="raq-fs">{props.item.sku}</div>

 )
 }
 return '';
}

const ShowPrice = (props) => {

 if(props.show_price === true && props.is_hide_price !== true ){
 return(
 <div className="raq-fs">
    {
        props.item.item_cost !== ''?<PriceWithCurrency currency={props.currency} price={props.item.item_cost} currency_position={props.currency_position}/>: ''
    } 
    
    
    </div>

 )
 }
 return null;
}

const ShowImage = (props) => {
 if(props.show_image === true){
 return(
 <img src={props.item.image_url}
 alt="" />
 )

 }
 return '';
}
const ShowItemSubtotal = (props) => {
 if(props.show_item_total == true && props.is_hide_price !== true){
 return(
 <div className="text-end">

 {
        props.item.item_total !== ''?<PriceWithCurrency currency={props.currency} price={props.item.item_total} currency_position={props.currency_position}/>: ''
    } 
 </div>
 )
 }
 return '';
}


export const QuoteListProducts = (props) => {
 const button_color = wp.element.useState(props.data.quote_items.general.general.button_default_color);

 const wc_currency = wp.element.useState(props.data.quote_items,quote_list.wc_currency);
 wp.element.useEffect(()=> {
 
 jQuery(window).on("update_quantity_event", function(e,quotelist_data ){
 setQuoteItems(quotelist_data);
 
 }); 
 jQuery(window).on("clear_list_event", function(e,quotelist_data){
 setQuoteItems(quotelist_data);
 }); 
 jQuery(window).on("delete_event", function(e,quotelist_data){
 setQuoteItems(quotelist_data);
 }); 
 
 
 } ,[] );

 const onQuoteListItemRemove = (i) => {
 
 const newQuoteList = {...quoteItems};
 newQuoteList.quote_list.items.splice(i,1);
 setQuoteItems(newQuoteList);
 
 }

 
 const HideOrShowUpdateList = (props) => {
 

 if(props.data.additional_settings.update_list_button === true ){
 return(
 <button onClick={() => { 
        setQuoteItems(tempElexRqQuoteList);
        UpdateListApi();
 }} 
 style={{
 backgroundColor: props.color[0],
 border:'none'
 }} 
 className="update_list_btn btn btn-sm btn-primary px-4">
 {__('Update List','elex-request-a-quote')}
 </button>
 )
 }
 return '';
 
 }

 const IsPriceHidden = (props) => {
 if(true === props.is_hide_price){
 return null;
 }
 return(
 <div className="col-md-6 col-7 border-0 mb-2 border-bottom border-secondary">
 <ShowSubTotal is_hide_price ={props.is_hide_price} currency= {props.currency} currency_position= {props.currency_position} sub_total ={props.items.quote_list.sub_total} show_sub_total={props.items.settings.show_on_product_table.each_product_subtotal } />
 <ShowTax is_hide_price ={props.is_hide_price} currency= {props.currency} currency_position= {props.currency_position} tax_value ={props.items.quote_list.tax} show_tax={props.items.settings.show_on_product_table.taxes } />
 
 <ShowTotal is_hide_price ={props.is_hide_price} currency= {props.currency}  currency_position= {props.currency_position} total ={props.items.quote_list.total} show_sub_total={props.items.settings.show_on_product_table.each_product_subtotal } />
 </div>
 )
 
 }
 
 const UpdateListApi = () => {

 const newQuoteList = {...quoteItems};
 let product_data = [];
 jQuery.each(newQuoteList.quote_list.items, function(k){
 
 let data = {
 product_id: newQuoteList.quote_list.items[k].product_id,
 quantity : newQuoteList.quote_list.items[k].quantity,
 variation_id : newQuoteList.quote_list.items[k].variation_id,

 }
 product_data.push(data);

 });
 
 jQuery.ajax({
 type: 'post',
 url: request_a_quote_ajax_obj.ajax_url,
 data: {
 action: 'elex_raq_update_quote_list',
 data: product_data,
 ajax_raq_nonce : request_a_quote_ajax_obj.nonce,
 
 },
 success: function(data){
 if (data.success === true) {
 jQuery(window).trigger("update_quantity_event", data.data.quote_list_data);
 
 } 
 }
 
 });

 }
 const QuoteItems = (props) => {
 const UpdateLocalList = (newQuantity , product_id , variation_id) => {
 const newQuoteList = {...quoteItems};
 jQuery.each(newQuoteList.quote_list.items, function(k){
 if(newQuoteList.quote_list.items[k].product_id === product_id &&
 newQuoteList.quote_list.items[k].variation_id === variation_id
 ){
 newQuoteList.quote_list.items[k].quantity = newQuantity;
 }

 });
 // Note: if we update state here component rerenders and input gets out of focus.
 tempElexRqQuoteList = newQuoteList
 }
 
 const UpdateItemQuantity = (product_id, quantity , variation_id ) => { 

 jQuery.ajax({
 type: 'post',
 url: request_a_quote_ajax_obj.ajax_url,
 data: {
 action: 'elex_raq_update_quantity',
 product_id: product_id,
 quote_list_id: props.quote_list_id,
 quantity: quantity,
 variation_id: variation_id,
 ajax_raq_nonce : request_a_quote_ajax_obj.nonce,
 
 },
 success: function(data){
 
 if (data.success === true) {
 jQuery(window).trigger("update_quantity_event", data.data.quote_list_data);

 jQuery("#elex-raq-updated-sucess-toast").addClass("show");
 setTimeout(function() {
 jQuery("#elex-raq-updated-sucess-toast").removeClass("show");
 }, 3000);
 
 } 
 }
 
 });
 
 }
 

 
 
 
 
 const ShowQuantity = (props) => {
 let is_disabled = ''
 if(props.item.child === true ){
 is_disabled = "disabled";
 }

 if(props.show_quantity === true && ! props.item.child ){
 return(
 <input type="number" min="1" id={props.item.product_id} onChange={(e)=> {UpdateLocalList(e.target.value,props.item.product_id ,props.item.variation_id ); (props.is_update_btn_enabled === false) ? UpdateItemQuantity(props.item.product_id,(e.target.value) , props.item.variation_id): ''; } } defaultValue={props.item.quantity} className="form-control product_quantity px-1" />
 
 )
 }
 if(props.show_quantity === true && props.item.child ){
 return(
 <input disabled type="number" min="1" id={props.item.product_id} onChange={(e)=> {UpdateLocalList(e.target.value,props.item.product_id ,props.item.variation_id ); (props.is_update_btn_enabled === false) ? UpdateItemQuantity(props.item.product_id,(e.target.value) , props.item.variation_id): ''; } } defaultValue={props.item.quantity} className="form-control product_quantity px-1" />
 
 )
 }
 return null;
 
 
 }



 const ShowProdcutDetails = (props) => {

 return (

 <a href={props.link} >{props.title}</a>
 )
 }
 

 return (

 <>
 <div 
 className="row text-start align-items-center border-0 pb-2 mb-2 border-bottom border-secondary">
 <div className="col-xl-1 col-md-1 col-2 mb-2">
 <RenderDelete props = {props}/>
 
 </div>
 <div className="col-xl-5 col-md-5 col-10 mb-2">
 <div className="d-flex gap-2">
 <div className="elex-raq-icon">
 <div className="ratio ratio-1x1">
 <ShowImage show_image={props.settings.show_on_product_table.product_image} item={props.item} />
 </div>
 </div>
 <div>
 <ShowProdcutDetails link={props.item.product_link} title = {props.item.title}/>
 <h6 className="raq-fs"></h6>
 <ShowSKU is_sku_checked={props.settings.show_on_product_table.product_sku} item={props.item} />
 </div>
 </div>
 </div>
 <div className="col-xl-2 col-md-2 col-3 offset-xl-0 offset-md-0 offset-2 mb-2">
 <ShowPrice currency={props.currency} currency_position= {props.currency_position} is_hide_price ={quoteItems.general.hide_add_to_cart.hide_price} show_price={props.settings.show_on_product_table.product_price} item={props.item} />
 </div>
 <div className="col-xl-2 col-md-2 col-3 mb-2 p-0">
 <ShowQuantity onUpdate={props.onUpdate} show_quantity ={props.settings.show_on_product_table.quantity} is_update_btn_enabled={props.is_update_btn_enabled} item={props.item} />
 
 </div>
 <div className="col-xl-2 col-md-2 col-4 text-end ps-2 mb-2">
 <ShowItemSubtotal  currency_position= {props.currency_position} is_hide_price ={quoteItems.general.hide_add_to_cart.hide_price} currency={props.currency} show_item_total={props.settings.show_on_product_table.each_product_subtotal} item={props.item} />
 
 
 </div>
 </div>
 </>
 
 )
 }

 

 const [quoteItems, setQuoteItems] = wp.element.useState(props.data.quote_items);

 if(undefined == quoteItems.quote_list){
    return '';
    }
    
 if(quoteItems.quote_list.items.length === 0){
 return '';
 }


 return( 
 
 <div className="shadow quote_list_page overflow-auto w-100 mb-3 flex-fill rounded-3 bg-white " id="elex_quote_list_page_div"> 
 <div className="quote_list_product_table">
 <div className="bg-secondary bg-opacity-10 p-3">
 <div className="row text-start">
 <div className="col-1"></div>
 <div className="col-sm-5 col-12">
 <h6 className="mb-0">{__('Product Detail','elex-request-a-quote')}</h6>
 </div>
 <ShowProductPrice is_hide_price ={quoteItems.general.hide_add_to_cart.hide_price} show_price={quoteItems.settings.show_on_product_table.product_price}/>
 
 <ShowProductQuantity show_quantity={quoteItems.settings.show_on_product_table.quantity}/>
 
 <ShowProductSubtotal is_hide_price ={quoteItems.general.hide_add_to_cart.hide_price} show_subtotal={quoteItems.settings.show_on_product_table.each_product_subtotal}/>
 </div>
 </div>

 <div className="p-3">
 {quoteItems.quote_list.items.map((item,i) => <QuoteItems onDelete={() => onQuoteListItemRemove(i)} ad_settings={quoteItems.additional_settings} settings={quoteItems.settings} currency= {quoteItems.quote_list.wc_currency} currency_position= {quoteItems.quote_list.currency_position} quote_list_id={quoteItems.quote_list.id} key={i} item={item} is_update_btn_enabled={quoteItems.additional_settings.update_list_button} />)}
 <div className="row text-start align-items-center mb-2 justify-content-end">

 <IsPriceHidden is_hide_price ={quoteItems.general.hide_add_to_cart.hide_price}  currency_position= {quoteItems.quote_list.currency_position} currency= {quoteItems.quote_list.wc_currency} items={quoteItems} />
 
 </div>

 <div className="d-flex flex-wrap justify-content-between flex-sm-row flex-column align-items-center gap-2">
 
 <HideOrShowAddMoreButton data={quoteItems.additional_settings} color={button_color} />
 <div className="d-flex gap-2">
 <HideOrShowClearList data={quoteItems.additional_settings} color={button_color} />
 <HideOrShowUpdateList data={quoteItems} color={button_color}/>
 </div>
 </div>
 </div>
 </div>
</div>

)
}