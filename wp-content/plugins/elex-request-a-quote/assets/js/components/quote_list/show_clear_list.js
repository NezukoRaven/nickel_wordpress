
import {ClearList} from './clear_list';
import __ from '../translate';

export const HideOrShowClearList = (props) => {
    if(props.data.clear_list === true){
        return(
            <button style={{
                backgroundColor: props.color[0],
                border:'none'

              }} 
               onClick={ClearList} 
               className="clear_list_btn btn btn-sm btn-primary px-4">{ __('Clear List','elex-request-a-quote')}
               </button>
        )
    }
    return '';
    
    }


