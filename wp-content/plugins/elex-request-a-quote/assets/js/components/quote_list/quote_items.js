import {UpdateQuantityLocal} from './quote_list_content';
import {decode} from 'html-entities';
export const RemoveProduct =(product_id,variation_id, callback) => {

    jQuery.ajax({
        type: 'post',
        url: request_a_quote_ajax_obj.ajax_url,
        data: {
            action: 'elex_raq_delete_item',
            product_id: product_id,
            variation_id: variation_id,
            ajax_raq_nonce : request_a_quote_ajax_obj.nonce,

        },
        success: function(data){
            if (data.success === true) {
                callback && callback(product_id);
                jQuery(window).trigger("delete_event", data.data.quote_list_data);

            }
        }
    });
    

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

    if(props.show_price === true){
            return(
                <div className="raq-fs">{props.item.item_cost}</div>

            )
    }
    return null;
}

const ShowImage = (props) => {
    // if(props.show_image === true){
    //     return(
    //         <img src={props.item.image_url}
    //                             alt="" />
    //     )

    // }
    return '';
}
const ShowItemSubtotal = (props) => {
    if(props.show_item_total == true){
        return(
            <div className="text-end">
            {props.item.item_total + '' +decode(props.currency)}
            </div>
        )
    }
    return '';
}
