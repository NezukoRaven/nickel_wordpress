 import __ from '../../translate';

const RenderInputField = ({ data, connected_to, default_image }) => {
  const [image, setImage] = wp.element.useState(default_image[0]);
  const [file, setFile] = wp.element.useState(null);
  const [checkboxes, setCheckboxes] = wp.element.useState([]);

  const TriggerInput = (e) => {
    jQuery("#file_uploaded").click();
  };

  const onImageChange = (event, connected) => {
    if (event.target.files && event.target.files[0]) {
      setFile(event.target.files);

      var reader = new FileReader();
      reader.onloadend = function () {
        setImage(reader.result);
      };
      reader.readAsDataURL(event.target.files[0]);
    }

    jQuery("#remove_uploaded").on("click", function () {
      jQuery("#file_uploaded").val("");
      jQuery("#remove_uploaded").addClass("d-none");
      jQuery(".upload_file").removeClass("d-none");
    });

    jQuery("#file_uploaded").bind("change", function () {
      if (this.files[0].size > 0) {
        jQuery("#remove_uploaded").removeClass("d-none");
      }
      if (this.files[0].size > 2000000) {
        alert("File Size should be less than 2MB.");
        jQuery(this).val("");
      }
      if (this.files[0].size == 0) {
        alert("File Size should not be zero.");
        jQuery(this).val("");
      }
    });

    jQuery("#elex-raq-import-file-display").removeClass("d-none");
    jQuery(".elex-raq-upload-file-remove").removeClass("d-none");
    jQuery(".elex-raq-import-file-display-title").removeClass("d-none");

    jQuery(".upload_file")
      .closest(".elex-raq-min-width-btn")
      .addClass("d-none");
    let file_name = event.target.files[0].name;
    jQuery(".elex-raq-import-file-display-title").html(file_name);

    jQuery(".elex-raq-upload-file-remove").click(function () {
      jQuery(".elex-raq-upload-file-remove").addClass("d-none");
      jQuery("#elex-raq-import-file-display").addClass("d-none");
      jQuery(".elex-raq-import-file-display-title").addClass("d-none");
      jQuery(".elex-raq-min-width-btn").removeClass("d-none");
      jQuery(".upload_file").val("");
      setImage(default_image[0]);
    });
  };

  let is_required =
    "true" === data.mandatory || true === data.mandatory ? "required" : "";

  if (["text", "number", "email", "date"].includes(data.type)) {
    let placeholder_text = data.placeholder
    return (
      <input
        required={is_required}
        name={connected_to}
        type={data.type}
        className="form-control"
        placeholder= {__(placeholder_text ,'elex-request-a-quote')}
      />
    );
  }
  if ("tel" === data.type) {
    let placeholder_text = data.placeholder
    return (
      <input
        required={is_required}
        name={connected_to}
        type={data.type}
        pattern="[0-9]*"
        minLength="9"
        maxLength="12"
        className="form-control"
        placeholder={__(placeholder_text ,'elex-request-a-quote')}
      />
    );
  }

  if ("textarea" === data.type) {
    return (
      <textarea
        required={is_required}
        name={connected_to}
        id=""
        cols="30"
        rows="4"
        class="form-control"
      ></textarea>
    );
  }

  if ("image" === data.type) {
    let placeholder_text = data.placeholder
    return (
      <>
        <div className="d-flex align-items-center gap-3 ">
          <div className="elex-rqst-profile-img">
            <div className="ratio ratio-1x1">
              <img src={image} alt="" name="" classname="w-100" />
            </div>
          </div>

          <input
            id="file_uploaded"
            name={connected_to}
            type="file"
            accept="image/*"
            onChange={(e) => {
              onImageChange(e, connected_to);
            }}
            required={is_required}
            placeholder={__(placeholder_text ,'elex-request-a-quote')}
            className="invisible pe-auto upload_file position-absolute file-upload-input
                     top-0 start-0 w-100 h-100 opacity-0"
          />
          <div
            onClick={TriggerInput}
            className="btn pe-auto upload_image position-relative"
          >
            <span className="pe-auto">{__('Upload Image','elex-request-a-quote')}</span>
          </div>
        </div>

        <div id="elex-raq-import-file-display" class="">
          <div class="gap-2  d-flex align-items-center mb-2">
            <span class="elex-raq-import-file-display-title text-primary"></span>
            <div
              id="remove_uploaded"
              class="  btn btn-sm btn-white rounded-circle primary-hover d-none elex-raq-upload-file-remove"
              data-bs-toggle="tooltip"
              data-bs-custom-class="tooltip-outline-primary"
              data-bs-placement="bottom"
              title=""
              data-bs-original-title="Cancel"
            >
              <svg
                xmlns="http://www.w3.org/2000/svg"
                width="14"
                height="14"
                viewBox="0 0 320 512"
              >
                <path d="M310.6 150.6c12.5-12.5 12.5-32.8 0-45.3s-32.8-12.5-45.3 0L160 210.7 54.6 105.4c-12.5-12.5-32.8-12.5-45.3 0s-12.5 32.8 0 45.3L114.7 256 9.4 361.4c-12.5 12.5-12.5 32.8 0 45.3s32.8 12.5 45.3 0L160 301.3 265.4 406.6c12.5 12.5 32.8 12.5 45.3 0s12.5-32.8 0-45.3L205.3 256 310.6 150.6z"></path>
              </svg>
            </div>
          </div>
        </div>
      </>
    );
  }
  if ("url" === data.type) {
    return (
      <input
        required={is_required}
        name={connected_to}
        type={data.type}
        pattern="https://.*"
        className="form-control"
        placeholder={data.placeholder}
      />
    );
  }

  if ("checkbox" === data.type || "radio" === data.type) {
    let myclass = is_required + "field";
    return (
      <div
        id={"my_" + data.type}
        className={
          "d-flex " +
          "gap-3 " +
          "align-items-center " +
          " custom_field " +
          is_required +
          "field"
        }
      >
        <Options
          data={data.options}
          type={data.type}
          is_required={is_required}
          name={connected_to}
          label={data.slug}
        />
      </div>
    );
  }
  return null;
};
const GetIputForRadioCheckBox = ({ type, label, value, name, is_required }) => {
  if (type === "checkbox") {
    let name_of_checkbox = name + "[]";
  }
  return (
    <>
      {type === "checkbox" && (
        <input
          type="checkbox"
          value={value}
          name={name}
          class="form-check-input m-0"
        ></input>
      )}
      {type === "radio" && (
        <input
          required={is_required}
          type="radio"
          value={value}
          name={name}
          class="form-check-input m-0"
        ></input>
      )}
    </>
  );
};

const Options = (props) => {
  const data = props.data;
  const type = props.type;
  const label = props.label;
  const name = props.name;
  const is_required = props.is_required;

  return data.map((i) => {
    return (
      <div className="d-flex gap-2 align-items-center" key={i}>
        <GetIputForRadioCheckBox
          is_required={is_required}
          index={i}
          type={type}
          name={name}
          value={i.value}
          label={label}
        />
        <label for="">{i.label}</label>
      </div>
    );
  });
};
const IsMandatory = ({ is_mandatory }) => {
  if (true === is_mandatory) {
    return <span class="text-danger">*</span>;
  }
  return null;
};

export const QuoteForm = (props) => {
  const [disabled, setDisabled] = wp.element.useState(false);
  const color = wp.element.useState(props.color);
  const title =  props.form.title

  const handleFormSubmit = async (event) => {
    event.preventDefault();
    setDisabled(true);

    const form = new FormData(document.getElementById("request_a_quote_form"));

    var checkedValues = [];
    let flag = false;
    jQuery('#request_a_quote_form input[type="checkbox"]').each(function () {
      let checkboxName = jQuery(this).attr("name");
      let checkedValues = [];

      jQuery(
        "#request_a_quote_form input[name=" + checkboxName + "]:checked"
      ).each(function () {
        checkedValues.push(jQuery(this).val());
      });

      form.append(checkboxName, checkedValues);

      if (
        jQuery("#my_checkbox").hasClass("custom_field") &&
        jQuery("#my_checkbox").hasClass("requiredfield") &&
        checkedValues.length < 1
      ) {
        alert("Select All the Mandatory Fields.");
        setDisabled(false);
        flag = true;
        return false;
      }
    });

    if (flag) {
      return;
    }
    // select all checkboxes in the form
    var checkboxes = jQuery('#request_a_quote_form input[type="checkbox"]');

    // loop through each checkbox and get its name attribute
    checkboxes.each(function () {
      let checkboxName = jQuery(this).attr("name");
      let selected_val = jQuery(
        " input[name=" + checkboxName + "]:checked"
      ).val();
    });

    var link = request_a_quote_ajax_obj.ajax_url;
    link += "?action=elex_raq_submit_form";
    form.append("ajax_raq_nonce", request_a_quote_ajax_obj.nonce);
    form.append(
      "cart_item",
      jQuery(document.getElementById("request_a_quote_form")).serialize()
    );
    jQuery.ajax({
      type: "post",
      url: link,
      data: form,
      processData: false,
      contentType: false,
      enctype: "multipart/form-data",
      success: function (data) {
        if (data.success === true) {
          if ("" != data.data.redirection_url) {
            window.location.href = data.data.redirection_url;
          } else {
            window.location.href = "/quote-received-page";
          }
        }
      },
    });
  };

  let count = "default";
  let fileData = {};

  const FormInput = (props) => {
    const [TypeComponent, SetType] = wp.element.useState(props.data.type);

    let connected_to = props.data.connected_to;
    const label_name =props.data.name
    const title =props.data.title

    if (props.data.connected_to === "default") {
      if (count === "default") {
        count = 0;
        connected_to = "default";
        count++;
      } else {
        connected_to = "default_" + count;
        count++;
      }
    }

    return (
      <>
        <div className="mb-3">
          <label for="">
          {__(label_name,'elex-request-a-quote')}
            <IsMandatory is_mandatory={props.data.mandatory} />
          </label>
          <RenderInputField
            default_image={props.default_image}
            data={props.data}props
            connected_to={connected_to}
          />
        </div>
      </>
    );
  };

  return (
    <div className="shadow w-100 mb-3 rounded-3 bg-white p-3">
      <h5 className="text-center"> {__(title,'elex-request-a-quote')}</h5>
      <form
        method="post"
        id="request_a_quote_form"
        name="request_a_quote_form"
        enctype="multipart/form-data"
        onSubmit={handleFormSubmit}
      >
        {props.form.fields.map((field, i) => {
          return (
            <FormInput
              key={i}
              data={field}
              count={count}
              default_image={props.default_image}
              color={color}
            />
          );
        })}

        <button
         style={{
          backgroundColor: props.color[0],
          border:'none'
        }} 
          disabled={disabled}
          type="submit"
          className=" eraq-send-request btn btn-sm btn-primary fw-light"
        >
        {__('Send Request','elex-request-a-quote')}
        </button>
      </form>
    </div>
  );
};
