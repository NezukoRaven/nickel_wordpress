import __ from '../translate';

export const HideOrShowAddMoreButton = (props) => {

    if(props.data.add_more_items_button === true){
        return(
            <a href={props.data.add_more_items_button_redirection}><button style={{
                backgroundColor: props.color[0],
                border:'none'

              }}  
              className="add_more_items_btn btn btn-sm btn-primary px-4">{ __(props.data.add_more_items_button_label , 'elex-request-a-quote' )}</button></a>
        )
    }
    return '';
    
}