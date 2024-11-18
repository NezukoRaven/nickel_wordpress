const { __ } = wp.i18n;

export const FormsettingHeader  = (props) => {

    const [title, setTitle] = wp.element.useState(props.title);
    const [url, setUrl] = wp.element.useState(props.redirection_url);
    const [msg, setSuccessMsg] = wp.element.useState(props.success_message);
    const [showForm, SetShowFormToggle] = wp.element.useState(props.show_form);

    


    return(<><div className="pt-3">
    <h5 className="fw-bold">{__('Form Settings','elex-request-a-quote')}</h5>
    </div><div className="p-3">
        <div className="row">
            <div className="col-12">


                <div class="row align-items-center mb-3">
                    <div class="col-lg-4 col-md-6">
                        <div class="d-flex justify-content-between align-items-center">
                            <h6 class="mb-0">{__('Show Request a Quote','elex-request-a-quote')}</h6>
                            
                        </div>
                    </div>
                    <div class="col-lg-5 col-md-6">
                        <label class="elex-switch-btn">
                            <input name="show_form" onChange={(e) => SetShowFormToggle(e.target.value)} value={showForm} type="checkbox" />
                            <div class="elex-switch-icon round"></div>
                        </label>

                        <div>
                            <small class="text-secondary">
                            {__('Turn this Off, if you want to use any third party form to get the quote requests.','elex-request-a-quote')}
                                
                            </small>
                        </div>
                    </div>
                </div>
                <div className="row align-items-center mb-3">
                    <div className="col-lg-4 col-md-6">
                        <div className="d-flex justify-content-between align-items-center">
                            <h6 className="mb-0">{__('Form Header Title','elex-request-a-quote')}</h6>
                            <svg xmlns="http://www.w3.org/2000/svg" width="26" height="26" viewBox="0 0 26 26">
                                <g id="tt" transform="translate(-384 -226)">
                                    <g id="Ellipse_1" data-name="Ellipse 1"
                                        transform="translate(384 226)" fill="#f5f5f5" stroke="#000"
                                        stroke-width="1">
                                        <circle cx="13" cy="13" r="13" stroke="none" />
                                        <circle cx="13" cy="13" r="12.5" fill="none" />
                                    </g>
                                    <text id="_" data-name="?" transform="translate(392 247)"
                                        font-size="20" font-family="Roboto-Bold, Roboto"
                                        font-weight="700">
                                        <tspan x="0" y="0">?</tspan>
                                    </text>
                                </g>
                            </svg>
                        </div>
                    </div>
                    <div className="col-lg-4 col-md-6">
                        <input name="title" onChange={(e) => setTitle(e.target.value)} type="text" value={title} className="form-control" placeholder="Fill Your Details" />
                    </div>
                </div>

                <h5 className="fw-bold mb-3">{__('Form Submit Actions','elex-request-a-quote')}</h5>

                <div className="row align-items-center mb-3">
                    <div className="col-lg-4 col-md-6">
                        <div className="d-flex justify-content-between align-items-center">
                            <h6 className="mb-0">{__('"Send Request" Button Redirectional URl','elex-request-a-quote')}</h6>
                            <svg xmlns="http://www.w3.org/2000/svg" width="26" height="26" viewBox="0 0 26 26">
                                <g id="tt" transform="translate(-384 -226)">
                                    <g id="Ellipse_1" data-name="Ellipse 1"
                                        transform="translate(384 226)" fill="#f5f5f5" stroke="#000"
                                        stroke-width="1">
                                        <circle cx="13" cy="13" r="13" stroke="none" />
                                        <circle cx="13" cy="13" r="12.5" fill="none" />
                                    </g>
                                    <text id="_" data-name="?" transform="translate(392 247)"
                                        font-size="20" font-family="Roboto-Bold, Roboto"
                                        font-weight="700">
                                        <tspan x="0" y="0">?</tspan>
                                    </text>
                                </g>
                            </svg>
                        </div>
                    </div>
                    <div className="col-lg-4 col-md-6">
                        <input type="text"  name="redirection_url" onChange={(e) => setUrl(e.target.value)} value={url} className="form-control" placeholder="https://example.com/sample" />
                    </div>
                </div>

                <div className="row align-items-center mb-3">
                    <div className="col-lg-4 col-md-6">
                        <div className="d-flex justify-content-between align-items-center">
                            <h6 className="mb-0">{__('"Send Request" Success Message','elex-request-a-quote')}</h6>
                            <svg xmlns="http://www.w3.org/2000/svg" width="26" height="26" viewBox="0 0 26 26">
                                <g id="tt" transform="translate(-384 -226)">
                                    <g id="Ellipse_1" data-name="Ellipse 1"
                                        transform="translate(384 226)" fill="#f5f5f5" stroke="#000"
                                        stroke-width="1">
                                        <circle cx="13" cy="13" r="13" stroke="none" />
                                        <circle cx="13" cy="13" r="12.5" fill="none" />
                                    </g>
                                    <text id="_" data-name="?" transform="translate(392 247)"
                                        font-size="20" font-family="Roboto-Bold, Roboto"
                                        font-weight="700">
                                        <tspan x="0" y="0">?</tspan>
                                    </text>
                                </g>
                            </svg>
                        </div>
                    </div>
                    <div className="col-lg-4 col-md-6">
                        <input type="text"  name="success_message" onChange={(e) => setSuccessMsg(e.target.value)} value={msg}  className="form-control" placeholder="Your request has been sent successfully" />
                    </div>
                </div>
            </div>
        </div>
    </div></>)

}


