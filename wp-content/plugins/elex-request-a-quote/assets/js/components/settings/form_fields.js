// import {Checkbox} from '../form/fields/checkbox';
import { DragDropContext, Droppable, Draggable } from 'react-beautiful-dnd';
import Chip from 'material-ui-chip-input';
import ChipInput from 'material-ui-chip-input';
import {UpdateQuoteMsg} from '../toast_message/update_message';
const { __ } = wp.i18n;


export const FormField = (props) => {

    const field   = props.data;
    const chipvalues = props.chipvalues;

    const [is_editing, setIsEditing] = wp.element.useState(field.is_editing);
    const [is_radio_checkbox, setValueForIsradio_checkbox] = wp.element.useState(false);

    const [placeHolderText, setPlaceHolderText] = wp.element.useState("Enter your Placeholder");
    
    const SaveField = (is_new_field) => {

        <UpdateQuoteMsg />
        const newField = {...field}
        const data = {
           name: newField.name,
           type:newField.type,
           connected_to :newField.connected_to,
           mandatory:newField.mandatory,
           placeholder:newField.placeholder,
           is_editing:false,
           is_radio_checkbox:newField.is_radio_checkbox,
           options:newField.options,
           deletable:newField.deletable,
           key:newField.key
        }

        jQuery.ajax({
            type: "post",
            url: raq_formsetting_ajax_object.ajax_url,
            data: {
              action: (is_new_field === true) ? 'req_frm_add_field': 'req_frm_edit_field',
              ajax_raq_nonce: raq_formsetting_ajax_object.nonce,
              data: data,
            },
            success: function(data) {

                if(data.data.code === 2){
                    jQuery("#elex-raq-updated-sucess-toast").addClass("show");
                }
                if(data.data.code === 1){
                    jQuery("#elex-raq-saved-sucess-toast").addClass("show");
                }
                if(data.data.code === 3){
                    jQuery("#elex-raq-duplicate-toast").addClass("show");
                }
                if(data.data.code === 4){
                    jQuery("#elex-raq-minimum-options-toast").addClass("show");
                }
                
                setTimeout(function() {

                    jQuery("#elex-raq-updated-sucess-toast").removeClass("show");
                    jQuery("#elex-raq-saved-sucess-toast").removeClass("show");
                    jQuery("#elex-raq-duplicate-toast").removeClass("show");
                    jQuery("#elex-raq-minimum-options-toast").removeClass("show");


                }, 3000);
        
            }
           
          })

    
    }

    const DeleteButton = (props) => {
        if(props.deletable === true){

            return(

                <button onClick={props.onDelete} type="button" class="delete_field btn btn-sm btn-white rounded-circle border-0 elex-rqust-quote-btn-pink-hover"
                data-bs-custom-class="tooltip-outline-danger" data-bs-toggle="tooltip" data-bs-placement="bottom"
                title="Delete">
                <i className="fa-regular fa-trash-can"></i>
            </button>
            );
        }
        return(

            <button onClick={props.onDelete} disabled type="button" class="delete_field btn btn-sm btn-white rounded-circle border-0 elex-rqust-quote-btn-pink-hover"
            data-bs-custom-class="tooltip-outline-danger" data-bs-toggle="tooltip" data-bs-placement="bottom"
            title="Delete">
            <i className="fa-regular fa-trash-can"></i>
        </button>
        )
        
    }
    
    const DragEnter = (e,callback) => {
        callback && callback();
    }

    const onDragStart = (e,callback) => {
        setIsEditing(false);
        
        callback && callback();
    }
    const dragEnd = (e,callback) => {
        
        callback && callback();
    }
   
const SaveButton = props => {
    if(is_editing === true){
       return(
        <button type="button" onClick={ e=> {SaveField(props.is_new_field);setIsEditing(false);}}  className="save_field btn btn-sm btn-success rounded-circle"
        data-bs-custom-class="tooltip-outline-success" data-bs-toggle="tooltip" data-bs-placement="bottom"
        title="Save">
        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 500 500" width="14" height="14">
            <path d="M470.6 105.4c12.5 12.5 12.5 32.8 0 45.3l-256 256c-12.5 12.5-32.8 12.5-45.3 0l-128-128c-12.5-12.5-12.5-32.8 0-45.3s32.8-12.5 45.3 0L192 338.7 425.4 105.4c12.5-12.5 32.8-12.5 45.3 0z"
                fill="#fff" stroke="#fff" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" /></svg>

    </button>
       )
    }
    return null;
}
    const EditButton = props => {

        if(is_editing === false){
          return(
            <button type="button"   onClick={e => { setIsEditing(true);   }}  className="edit_field btn btn-sm btn-white rounded-circle elex-rqust-quote-btn-purple-hover"
            // <button type="button" onClick={(e) => {setIsEditing(true)}}  className="edit_field btn btn-sm btn-white rounded-circle elex-rqust-quote-btn-purple-hover"
            data-bs-custom-class="tooltip-outline-purple" data-bs-toggle="tooltip" data-bs-placement="bottom"
            title="Edit">
            <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 18.638 18.638">
                <g id="Icon_feather-edit-3" data-name="Icon feather-edit-3" transform="translate(-3.486 -3.183)">
                    <path id="Path_29" data-name="Path 29" d="M17.8,30.363H26.12" transform="translate(-4.996 -9.486)" fill="none" stroke="#000" stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" />
                    <path id="Path_30" data-name="Path 30" d="M16.964,4.775a1.92,1.92,0,0,1,2.773,0,2.308,2.308,0,0,1,0,3.067L7.952,19.344,4.486,20.878l.693-3.834Z" transform="translate(0 0)" fill="none" stroke="#000" stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" />
                </g>
            </svg>
        </button>
          )
        }
        return <SaveButton is_editing={is_editing} is_new_field={field.is_new_field} />;
    }
    const handleAddChip = (chip , callback) => {
    
        value = chip;
     }

    return(
       
            <><tr
            draggable
            onDragEnd={e => props.onDragEnd(e)}
            // onDrop={e => props.onDragEnd(e) } 
            onDragEnter={(e) => props.onDragEnter(e)}
            onDragStart={(e) => props.onDragStart(e)}
            className="form_field_row">

            <td className="grab">
                <svg id="Drag_icon" className="elex-blue-icon-hover-btn" data-name="Drag icon" xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 18 18">
                    <g id="Rectangle_301" data-name="Rectangle 301" fill="#fff"
                        stroke="#707070" stroke-width="1">
                        <rect width="8" height="8" rx="2" stroke="none" />
                        <rect x="0.5" y="0.5" width="7" height="7" rx="1.5"
                            fill="none" />
                    </g>
                    <g id="Rectangle_302" data-name="Rectangle 302"
                        transform="translate(10)" fill="#fff" stroke="#707070"
                        stroke-width="1">
                        <rect width="8" height="8" rx="2" stroke="none" />
                        <rect x="0.5" y="0.5" width="7" height="7" rx="1.5"
                            fill="none" />
                    </g>
                    <g id="Rectangle_303" data-name="Rectangle 303"
                        transform="translate(10 10)" fill="#fff" stroke="#707070"
                        stroke-width="1">
                        <rect width="8" height="8" rx="2" stroke="none" />
                        <rect x="0.5" y="0.5" width="7" height="7" rx="1.5"
                            fill="none" />
                    </g>
                    <g id="Rectangle_304" data-name="Rectangle 304"
                        transform="translate(0 10)" fill="#fff" stroke="#707070"
                        stroke-width="1">
                        <rect width="8" height="8" rx="2" stroke="none" />
                        <rect x="0.5" y="0.5" width="7" height="7" rx="1.5"
                            fill="none" />
                    </g>
                </svg>
            </td>
            <td>
                <Label key={field.id} value={field.name}  is_editing={is_editing} disabled={!is_editing} onChangeLabel={txt => {field.name = txt;  props.onChange(field)} } />
            </td><td>
                <FieldType key={field.id} value={field.type}   disabled={!is_editing} onChangeFieldType={txt => {field.type = txt; (txt === 'radio' || txt === 'checkbox') ? ( field.is_radio_checkbox = true  ): (field.is_radio_checkbox = false ); props.onChange(field)}} 
                onLoadFieldType={txt => {field.type = txt; (txt === 'radio' || txt === 'checkbox') ? ( field.is_radio_checkbox = true  ): (field.is_radio_checkbox = false ); props.onChange(field)}}
                
                />
            </td><td>
            <Placeholder key={field.id} value={field.placeholder}  is_editing={is_editing} disabled={!is_editing || !is_radio_checkbox} onChangePlaceholder={txt => {field.placeholder = txt;  props.onChange(field)} }/>
               
            </td><td>
                <div className="d-flex gap-2">
                    <ConnectedField key={field.id} value={field.connected_to} disabled={!is_editing} onChangeConnectedField={txt => { field.connected_to = txt;  props.onChange(field)}} />
                </div>

            </td>
            <td>
                <Checkbox key={field.id} value={field.mandatory} disabled={!is_editing} onChangeCheckbox={txt => {field.mandatory = txt;props.onUpdateToggle(field)}} />

            </td>

            <td>
                <div class="d-flex gap-2">
                    <EditButton  key={field.id} />
                    <DeleteButton onDelete={props.onDelete} deletable={field.deletable} />
                </div>

            </td>
        </tr>
      
           <ChipInputField onChangeChip={txt => {field.options = txt; props.onChange(field)}}  value={field.options} is_radio_checkbox={field.is_radio_checkbox} is_editing={is_editing} disabled={!is_editing}  />

        </>
         
        )
}



const Checkbox = (props) => {
        
    const { disabled,is_editing,onChangeCheckbox} = props;
    const [value, setValue] = wp.element.useState(props.value)

    if(value === true){
        return(

            <label className="elex-switch-btn">
            <input type="checkbox"   onChange={e => { setValue(e.target.checked);  onChangeCheckbox(e.target.checked) }}  checked />

                <div className="elex-switch-icon round"></div>
            </label>
        
        )
    }
    return(
        <label className="elex-switch-btn">
        <input type="checkbox"  onChange={e => { setValue(e.target.checked);  onChangeCheckbox(e.target.checked) }}  />

                <div className="elex-switch-icon round"></div>
            </label>
    
    )


}
const Label = props => {

      const {disabled,is_editing,onChangeLabel} = props;
    const [value, setValue] = wp.element.useState(props.value)
    return(
        <input disabled={disabled} value={value} 
        onChange={e => {setValue(e.target.value);  onChangeLabel(e.target.value) }} placeholder="Enter your Label" className="form-control" />

    )

}

const FieldOptions = () => {
    return(
      <><option value="">{__('Select a type','elex-request-a-quote')}</option><option value="text">{__('Text','elex-request-a-quote')}</option><option value="textarea">{__('Textarea','elex-request-a-quote')}</option><option value="email">{__('Email','elex-request-a-quote')}</option><option value="url">{__('Url','elex-request-a-quote')}</option><option value="image">{__('Image','elex-request-a-quote')}</option><option value="checkbox">{__('Checkbox','elex-request-a-quote')}</option><option value="date">{__('Date','elex-request-a-quote')}</option><option value="number">{__('Number','elex-request-a-quote')}</option><option value="radio">{__('Radio','elex-request-a-quote')}</option><option value="tel">{__('Tel','elex-request-a-quote')}</option></>
    )
}

const FieldType = props => {
    const {disabled,is_editing,onChangeFieldType,onLoadFieldType} = props;
    const [value, setValue] = wp.element.useState(props.value)
      return (<select disabled={disabled} value={value} 
        onChange={e => { 
            setValue(e.target.value);  
        onChangeFieldType(e.target.value);
         }
        } className="form-select" name="" id="field_type">
      <FieldOptions />
</select>)
   

}

const ConnectedField = props => {
    const {disabled,is_editing,onChangeConnectedField} = props;
    const [value, setValue] = wp.element.useState(props.value)

        return (<select value={value} disabled={disabled} onChange={e => { setValue(e.target.value);  onChangeConnectedField(e.target.value) }} className="form-select" name="connected_to" id="connected_to">
        <ConnectedFieldOptions />
</select>)
   
}

const ConnectedFieldOptions = () => {
    return(
        <><option value="">{__('Select Connected To','elex-request-a-quote')}</option><option  value="billing_first_name">{__('billing_first_name','elex-request-a-quote')}</option><option value="billing_last_name">{__('billing_last_name','elex-request-a-quote')}</option><option value="billing_email">{__('billing_email','elex-request-a-quote')}</option><option value="billing_company">{__('billing_company','elex-request-a-quote')}</option><option value="billing_country">{__('billing_country','elex-request-a-quote')}</option><option value="billing_address_1">{__('billing_address_1','elex-request-a-quote')}</option><option value="billing_address_2">{__('billing_address_2','elex-request-a-quote')}</option><option value="billing_city">{__('billing_city','elex-request-a-quote')}</option><option value="billing_state">{__('billing_state','elex-request-a-quote')}</option><option value="billing_postcode">{__('billing_postcode','elex-request-a-quote')}</option><option value="billing_phone">{__('billing_phone','elex-request-a-quote')}</option><option value="order_comments">{__('order_comments','elex-request-a-quote')}</option><option value="default">{__('Custom Field','elex-request-a-quote')}</option></>
    )
  }

const Placeholder = props => {

    const {is_editing,onChangePlaceholder} = props;
    const [value, setValue] = wp.element.useState(props.value)
    if(is_editing){
        return (
            <input name=""  value={value}  onChange={e => { setValue(e.target.value);  onChangePlaceholder(e.target.value) }} type="text" className="form-control" />
        )
    }
    return (
        <input name=""  value={value} disabled  onChange={e => { setValue(e.target.value);  onChangePlaceholder(e.target.value) }} type="text" className="form-control" />
    )
  
}
  
const ChipInputField = props => {
    const {disabled,is_radio_checkbox,onChangeChip,is_editing} = props;
    const [value, setValue] = wp.element.useState(props.value);

    let ChipInputValues = []
        let boolVar = value.some( 
            v => { return typeof v == "object" } );
            if(boolVar){

                const ChipInputs = value.map((val,index)=>{
                ChipInputValues.push(val.label);
        })  

            }

    const RemoveChip = (chip , i,values) => {

        values.splice(i,1);
        setValue(values);
        onChangeChip(values);
    }

    const addChipvalues = (selected_values, e) => {

        const values = [...value];
        const newValue = selected_values;

        const newvalues = {...value};
                let new_values = [];
                if(Object.keys(newvalues).length  > 0 ){
                    if(typeof newvalues[0] === "object" ){
                        for (let property in newvalues) {
                                for(let key in newvalues[property]){
                                    
                                    if('label' === key){
                                        if(new_values.indexOf(newvalues[property][key]) === -1){
                                            newvalues[property][key] = newvalues[property][key].replace(/'/g, "");
                                        
                                            new_values.push(newvalues[property][key]);
                                            }
                                    }
                                    
                                }
                            
                    }
                        
                    }
                    
                }
                if(new_values.length == 0){
                        new_values = [...value];

                }
                
                let merged_values = new_values.concat(selected_values).filter((item, index, self) => {
                    return index === self.indexOf(item);
                    });
                setValue(merged_values);
                onChangeChip(merged_values);


        } 
       

    if(is_radio_checkbox === true){

        return(
          <tr>
              <td onDelete={(chip, index) => handleDeleteChip(chip, index , props.onChange )}  colspan="7">
                 <ChipInput onDelete={(chip , index) => { RemoveChip(chip , index,(boolVar === true) ? ChipInputValues :value); }} disabled={disabled} value ={(boolVar === true) ? ChipInputValues :value} 
                 onAdd = {( chip, e ) => addChipvalues(chip ,e ) }

                    placeholder={__('Enter values and click enter to add more','elex-request-a-quote')}
                    label= {__('Click Enter to add more','elex-request-a-quote')}
                    size="small" 
                    />
              </td>
         </tr>
      )
  }
  return null;
  
}



