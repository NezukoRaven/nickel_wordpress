
// import {FormsettingHeader} from './settings/form_setting_header';
import {FormField} from './settings/form_fields';
// import {SaveFormSettings} from './settings/form_setting_header';
import { DragDropContext, Droppable, Draggable } from 'react-beautiful-dnd';
import __ from '../components/translate';

export const FormSettings = (props) => {

    const [FieldItems , setFielditems] = wp.element.useState(props.data.fields);
    const [Title, setTitle] = wp.element.useState(props.data.title);
    const [url, setUrl] = wp.element.useState(props.data.redirection_url);
    const [msg, setSuccessMsg] = wp.element.useState(props.data.success_message);
    const [showForm, SetShowFormToggle] = wp.element.useState('false' == props.data.show_form ? false :true);

    const [createUser, setUser] = wp.element.useState(
       false
      );
      const [autoComplete, setAutoComplete] = wp.element.useState(
       false
      );
      const [recapcha, setRecapcha] = wp.element.useState(
       false
      );
    let dragSource = null;
    let dropTarget = null;

    const dragStart = (e, position) => {
        dragSource =position;
    };

      const dragEnter = (e, position) => {
        dropTarget = position;
      };

      const drop = (e, position) => {
        const copyFieldItems = [...FieldItems];

        const [dragItemContent] = copyFieldItems.splice(position, 1);
        dragItemContent.is_editing = false;
        copyFieldItems.splice(dropTarget, 0, dragItemContent); 

       
        setFielditems([...copyFieldItems]);

        jQuery.ajax({
            type: "post",
            url: raq_formsetting_ajax_object.ajax_url,
            data: {
              action: 'elex_raq_rearrange_fields',
              ajax_raq_nonce: raq_formsetting_ajax_object.nonce,
              source_index: position,
              destination_index: dropTarget,
            },
            success: function(data) {
                window.location.reload();
            }
          })


      };

      const reArrangeApi = (sourceIndex,destinationIndex) => {

      }

    const onClickEdit = () => {

        SetSaveFieldClasses("save_field btn btn-sm btn-success rounded-circle");
        SetEditFieldClasses("d-none edit_field btn btn-sm btn-white rounded-circle elex-rqust-quote-btn-purple-hover");

      }

    const AddNewField = () => {
        
        const newFormFields = [...FieldItems];
        newFormFields.push({
            name: "",
            type: 'text',
            placeholder: "",
            connected_to:"",
            status:"",
            mandatory: true,
            deletable: true,
            is_editing:true,
            is_new_field:true,
            is_radio_checkbox:false,
            options:[],

        });
        setFielditems(newFormFields);

    

    }

    const onFormFieldRowRemove = (i) => {
    
        const newFormFields = [...FieldItems];
       

        jQuery.ajax({
            type: "post",
            url: raq_formsetting_ajax_object.ajax_url,
            data: {
              action: 'req_frm_delete_field',
              ajax_raq_nonce: raq_formsetting_ajax_object.nonce,
              key: newFormFields[i].key,
            },
            success: function(data) {

                if(data.data.code === 200){
                    jQuery("#elex-raq-deleted").addClass("show");
                }
                
                setTimeout(function() {

                    jQuery("#elex-raq-deleted").removeClass("show");

                }, 3000);
             
        
            }
          })

          newFormFields.splice(i,1);
          setFielditems(newFormFields);

    
    }
    const UpdateField = (field,i) => {
       
         let newFormFields = [...FieldItems];
        newFormFields[i] = field;
        setFielditems(newFormFields);
       

    }
    const UpdateToggle = (field,i) => {
       
        let newFormFields = [...FieldItems];
       newFormFields[i] = field;
       setFielditems(newFormFields);

       jQuery.ajax({
        type: "post",
        url: raq_formsetting_ajax_object.ajax_url,
        data: {
          action: 'req_frm_toggle_field',
          ajax_raq_nonce: raq_formsetting_ajax_object.nonce,
          key: newFormFields[i].key,
        },
        success: function(data) {
            if(data.data.code === 200){
                jQuery("#elex-raq-updated-sucess-toast").addClass("show");
            }
            
            setTimeout(function() {
                jQuery("#elex-raq-updated-sucess-toast").removeClass("show");

            }, 3000);
            
    
        }
      })


   }
   
    const SaveFormSettings = (e) => {
        e.preventDefault();

        let newFormFields = [...FieldItems];

        jQuery.ajax({
            type: "post",
            url: raq_formsetting_ajax_object.ajax_url,
            data: {
              action: 'elex_raq_save_form_settings_data',
              ajax_raq_nonce: raq_formsetting_ajax_object.nonce,
                title: Title,
                show_form:showForm,
                redirection_url :url,
                success_message:msg,
                form_fields:newFormFields
 
            },
            success: function(data) {
                if(data.data.code === 1){
                    jQuery("#elex-raq-saved-sucess-toast").addClass("show");
                }
                setTimeout(function() {

                    jQuery("#elex-raq-saved-sucess-toast").removeClass("show");

                }, 3000);
                window.location.reload();

            }
          })
    
    }

   
    const CheckBox = () => {
        if(false === showForm ){
            return(
                <input name="show_form"  onChange={(e) =>  {SetShowFormToggle(e.target.checked)}}  value={showForm} type="checkbox" />

            )
        }
        return(
            <input name="show_form" checked onChange={(e) =>  {SetShowFormToggle(e.target.checked)}}  value={showForm} type="checkbox" />

        )
    }

    const CreateWPUser = () => {
       
          return (
            <input
            disabled
              name=""
              value={createUser}
              type="checkbox"
            />
          );
      
      };
      const AutoCompleteForm = () => {
          return (
            <input
            disabled
              name=""
              value={autoComplete}
              type="checkbox"
            />
          );
      
      };
      const AddreCAPTCHA = () => {
          return (
            <input
            disabled
              name=""
              value={recapcha}
              type="checkbox"
            />
          );
       
      };
    
    return  (<>
    
    <form method="POST">
        <div className="pt-3">
          <h5 className="fw-bold">
            {__("Form Settings", "elex-request-a-quote")}
          </h5>
        </div>

        <div className="p-3">
          <div className="row">
            <div className="col-12">
              <div class="row align-items-center mb-3">
                <div class="col-lg-4 col-md-6">
                  <div class="d-flex justify-content-between align-items-center">
                    <h6 class="mb-0">
                      {__(
                        "Show Request a Quote Form",
                        "elex-request-a-quote"
                      )}
                    </h6>
                  </div>
                </div>
                <div class="col-lg-5 col-md-6">
                  <label class="elex-switch-btn">
                    <CheckBox />
                    <div class="elex-switch-icon round"></div>
                  </label>

                  <div>
                    <small class="text-secondary">
                      {__(
                        "Enable this option to create a WordPress user for guest users after they submit the form.",
                        "elex-request-a-quote"
                      )}
                    </small>
                  </div>
                </div>
              </div>
              <div class="row align-items-center mb-3">
                <div class="col-lg-4 col-md-6">
                  <div class="d-flex justify-content-between align-items-center">
                    <h6 class="mb-0">
                      {__("Auto Complete Form", "elex-request-a-quote")}
                      <span className='elex_raq_go_premium_color'>{__('[Premium]' ,'elex-request-a-quote')}</span>
                    </h6>
                  </div>
                </div>
                <div class="col-lg-5 col-md-6">
                  <label class="elex-switch-btn">
                    <AutoCompleteForm />
                    <div class="elex-switch-icon round"></div>
                  </label>

                  <div>
                    <small class="text-secondary">
                      {__(
                        "Turn this On to autocomplete request quote form with user account details.",
                        "elex-request-a-quote"
                      )}
                    </small>
                  </div>
                </div>
              </div>
              <div class="row align-items-center mb-3">
                <div class="col-lg-4 col-md-6">
                  <div class="d-flex justify-content-between align-items-center">
                    <h6 class="mb-0">
                      {__(
                        "Create WordPress User",
                        "elex-request-a-quote"
                      )}
                      <span className='elex_raq_go_premium_color'>{__('[Premium]','elex-request-a-quote')}</span>

                    </h6>
                  </div>
                </div>
                <div class="col-lg-5 col-md-6">
                  <label class="elex-switch-btn">
                    <CreateWPUser />
                    <div class="elex-switch-icon round"></div>
                  </label>

                  <div>
                    <small class="text-secondary">
                      {__(
                        "Enable this option to create a WordPress user for guest users after they submit the form.",
                        "elex-request-a-quote"
                      )}
                    </small>
                  </div>
                </div>
              </div>
              <div class="row align-items-center mb-3">
                <div class="col-lg-4 col-md-6">
                  <div class="d-flex justify-content-between align-items-center">
                    <h6 class="mb-0">
                      {__(
                        "Enable reCAPTCHA",
                        "elex-request-a-quote"
                      )}
                      <span className='elex_raq_go_premium_color'>{__('[Premium]' ,'elex-request-a-quote')}</span>

                    </h6>
                  </div>
                </div>
                <div class="col-lg-5 col-md-6">
                  <label class="elex-switch-btn">
                    <AddreCAPTCHA />
                    <div class="elex-switch-icon round"></div>
                  </label>

                  <div>
                    <small class="text-secondary">
                      {__(
                        "Enable to activate reCAPTCHA v3 verification for submissions on the 'Request a Quote' form. ",
                        "elex-request-a-quote"
                      )}
                    </small>
                  </div>
                  <div>
                    <small class="text-secondary">
                      {__(
                        "To use reCAPTCHA, sign up for an ",
                        "elex-request-a-quote"
                      )}
                      {
                        <a target="_blank" href="https://cloud.google.com/recaptcha-enterprise/docs/create-key-website">{__('API Key Pair ' , 'elex-request-a-quote')}</a>
                      }
                       {__(
                        "for your site.",
                        "elex-request-a-quote"
                      )}
                    </small>
                  </div>
                </div>
              </div>
            {false !== recapcha && (
              <><div className="site_key row align-items-center mb-3">
                  <div className="col-lg-4 col-md-6">
                    <div className="d-flex justify-content-between align-items-center">
                      <h6 className="mb-0">
                        {__("Site Key", "elex-request-a-quote")}
                      </h6>
                      <ToolTip msg={__("Enter a Valid Site Key", "elex-request-a-quote")} />

                    </div>
                  </div>
                  <div className="col-lg-4 col-md-6">
                    <input
                      name="site_key"
                      onChange={(e) => setSiteKey(e.target.value)}
                      type="text"
                      value={site_key}
                      className="form-control"
                      placeholder={__(
                        "Enter your Site Key",
                        "elex-request-a-quote"
                      )} />
                  </div>
                </div><div className="secret_key row align-items-center mb-3">
                    <div className="col-lg-4 col-md-6">
                      <div className="d-flex justify-content-between align-items-center">
                        <h6 className="mb-0">
                          {__("Secret Key", "elex-request-a-quote")}
                        </h6>
                        <ToolTip msg={__("Enter a valid secret key. Quotes will not be accepted if entered incorrectly.", "elex-request-a-quote")} />
                      </div>
                    </div>
                    <div className="col-lg-4 col-md-6">
                      <input
                        name="private_key"
                        onChange={(e) => setSecretKey(e.target.value)}
                        type="text"
                        value={secret_key}
                        className="form-control"
                        placeholder={__(
                          "Enter your Secret key",
                          "elex-request-a-quote"
                        )} />
                    </div>
                  </div></>
            )}
             
              <div className="row align-items-center mb-3">
                <div className="col-lg-4 col-md-6">
                  <div className="d-flex justify-content-between align-items-center">
                    <h6 className="mb-0">
                      {__("Form Header Title", "elex-request-a-quote")}
                    </h6>
                    <div
                      type="button"
                      class=""
                      data-bs-toggle="tooltip"
                      data-bs-placement="top"
                      title={__(
                        "Enter the text you want to display as the header/title of the quote form.",
                        "elex-request-a-quote"
                      )}
                    >
                      <svg
                        xmlns="http://www.w3.org/2000/svg"
                        width="26"
                        height="26"
                        viewBox="0 0 26 26"
                      >
                        <g id="tt" transform="translate(-384 -226)">
                          <g
                            id="Ellipse_1"
                            data-name="Ellipse 1"
                            transform="translate(384 226)"
                            fill="#f5f5f5"
                            stroke="#000"
                            stroke-width="1"
                          >
                            <circle cx="13" cy="13" r="13" stroke="none" />
                            <circle cx="13" cy="13" r="12.5" fill="none" />
                          </g>
                          <text
                            id="_"
                            data-name="?"
                            transform="translate(392 247)"
                            font-size="20"
                            font-family="Roboto-Bold, Roboto"
                            font-weight="700"
                          >
                            <tspan x="0" y="0">
                              ?
                            </tspan>
                          </text>
                        </g>
                      </svg>
                    </div>
                  </div>
                </div>
                <div className="col-lg-4 col-md-6">
                  <input
                    name="title"
                    onChange={(e) => setTitle(e.target.value)}
                    type="text"
                    value={Title}
                    className="form-control"
                    placeholder={__(
                      "Fill Your Details",
                      "elex-request-a-quote"
                    )}
                  />
                </div>
              </div>
            </div>
          </div>
        </div>

        <h5 className="fw-bold mb-3">
          {__("Form Submit Actions", "elex-request-a-quote")}
        </h5>

        <div className="p-3">
          <div className="row">
            <div className="col-12">
              <div className="row align-items-center mb-3">
                <div className="col-lg-4 col-md-6">
                  <div className="d-flex justify-content-between align-items-center">
                    <h6 className="mb-0">
                      {__(
                        '"Send Request" Button Redirectional URl',
                        "elex-request-a-quote"
                      )}
                    </h6>
                    <div
                      type="button"
                      class=""
                      data-bs-toggle="tooltip"
                      data-bs-placement="top"
                      title={__(
                        'Please provide the page URL that you wish to redirect to, After clicking the "Send Request" button',
                        "elex-request-a-quote"
                      )}
                    >
                      <svg
                        xmlns="http://www.w3.org/2000/svg"
                        width="26"
                        height="26"
                        viewBox="0 0 26 26"
                      >
                        <g id="tt" transform="translate(-384 -226)">
                          <g
                            id="Ellipse_1"
                            data-name="Ellipse 1"
                            transform="translate(384 226)"
                            fill="#f5f5f5"
                            stroke="#000"
                            stroke-width="1"
                          >
                            <circle cx="13" cy="13" r="13" stroke="none" />
                            <circle cx="13" cy="13" r="12.5" fill="none" />
                          </g>
                          <text
                            id="_"
                            data-name="?"
                            transform="translate(392 247)"
                            font-size="20"
                            font-family="Roboto-Bold, Roboto"
                            font-weight="700"
                          >
                            <tspan x="0" y="0">
                              ?
                            </tspan>
                          </text>
                        </g>
                      </svg>
                    </div>
                  </div>
                </div>
                <div className="col-lg-4 col-md-6">
                  <input
                    type="text"
                    name="redirection_url"
                    onChange={(e) => setUrl(e.target.value)}
                    value={url}
                    className="form-control"
                    placeholder="https://example.com/sample"
                  />
                </div>
              </div>

              <div className="row align-items-center mb-3">
                <div className="col-lg-4 col-md-6">
                  <div className="d-flex justify-content-between align-items-center">
                    <h6 className="mb-0">
                      {__(
                        '"Send Request" Success Message',
                        "elex-request-a-quote"
                      )}
                    </h6>
                    <div
                      type="button"
                      class=""
                      data-bs-toggle="tooltip"
                      data-bs-placement="top"
                      title={__(
                        "Enter the text that you want to display as a message after the successful submission of the quote request.",
                        "elex-request-a-quote"
                      )}
                    >
                      <svg
                        xmlns="http://www.w3.org/2000/svg"
                        width="26"
                        height="26"
                        viewBox="0 0 26 26"
                      >
                        <g id="tt" transform="translate(-384 -226)">
                          <g
                            id="Ellipse_1"
                            data-name="Ellipse 1"
                            transform="translate(384 226)"
                            fill="#f5f5f5"
                            stroke="#000"
                            stroke-width="1"
                          >
                            <circle cx="13" cy="13" r="13" stroke="none" />
                            <circle cx="13" cy="13" r="12.5" fill="none" />
                          </g>
                          <text
                            id="_"
                            data-name="?"
                            transform="translate(392 247)"
                            font-size="20"
                            font-family="Roboto-Bold, Roboto"
                            font-weight="700"
                          >
                            <tspan x="0" y="0">
                              ?
                            </tspan>
                          </text>
                        </g>
                      </svg>
                    </div>
                  </div>
                </div>
                <div className="col-lg-4 col-md-6">
                  <input
                    type="text"
                    name="success_message"
                    onChange={(e) => setSuccessMsg(e.target.value)}
                    value={msg}
                    className="form-control"
                    placeholder={__(
                      "Your request has been sent successfully.",
                      "elex-request-a-quote"
                    )}
                  />
                </div>
              </div>
            </div>
          </div>
        </div><h5 className="fw-bold">
            {__('Form Field Settings','elex-request-a-quote')}
        </h5><div className="p-3">
            <div className="row">
                <div className="col-12">

                    <div className="bg-warning bg-opacity-10 p-2  mb-3 elex-rqst-quote-warning">
                        <small>{__("When you create custom fields, the placeholder for the email template will be created based on the label text (For eg: Upload Image will be @Upload_Image). If you make changes to the label text in the future, please make sure to use the updated placeholder in the email body. Also, the label text cannot be modified for orders placed before the change.",'elex-request-a-quote')}</small>
                    </div>
                    <div className="border rounded-3  mb-3">
                   
                            <table  id="table" className="table mb-0 align-middle" style={{ tableLayout: "fixed" }}>
                                <thead>
                                <tr className="table-light">
                                    <th width="30px"></th>
                                    <th scope="col-3">{__('Label Name','elex-request-a-quote')}</th>
                                    <th scope="col-3">{__('Field Type ','elex-request-a-quote')} </th>
                                    <th scope="col-3">{__('Placeholder ','elex-request-a-quote')}</th>
                                    <th scope="col-2">{__('Connected To ','elex-request-a-quote')}</th>
                                    <th scope="col-2" width="80px">{__('Required ','elex-request-a-quote')}</th>
                                    <th scope="col-1" width="90px">{__('Actions ','elex-request-a-quote')}</th>
                                </tr>
                                </thead>

                            <tbody className="border-top-0 form_field_container">
                                {FieldItems.map((field, i) => {
                                    return <FormField 
                                        key={i} 
                                        onDragEnd={e => drop(e, i)} 
                                        onDragEnter={(e) => dragEnter(e, i)} 
                                        onDragStart={(e) => dragStart(e, i)} 
                                        onEdit={onClickEdit}   
                                        data={field}
                                        // chipvalues = {(field.options[0]) ? field.options[0].label : []}
                                        onChange={field => UpdateField(field, i)} 
                                        onUpdateToggle={field => UpdateToggle(field, i)} 
                                        
                                        onDelete={() => onFormFieldRowRemove(i)}  
                                    />
                                })}
                            </tbody>

                        </table>

                        <button onClick={(e) => AddNewField() } type="button" className="add_new_field btn bg-primary bg-opacity-10 text-primary border border-primary m-2">
                        {__('Add New Field ','elex-request-a-quote')}</button>
                    </div>
                    <input type="hidden" name="elex_form_settings_nonce" value={raq_formsetting_ajax_object.nonce} />
                    <div className="m-3">
                        <button  name="submit" type="submit" onClick={SaveFormSettings}  className=" btn btn-primary">{__('Save Changes','elex-request-a-quote')}</button>
                    </div>
                </div>
            </div>
        </div>
        
        </form>
        </>)

}

jQuery(document).ready(function () {
    
    if(!window.form_settings){
        return;
    }
     let form_settings = raq_formsetting_ajax_object.form_settings;
     form_settings.fields = form_settings.fields.map(field => {
         field.id = Math.random();
         return field;   
     });
    if(document.getElementById("form_settings") !== null ){
     wp.element.render(
         <FormSettings data={form_settings} key={form_settings.title} />,
         document.getElementById('form_settings')
     ); 
    }
});