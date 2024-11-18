export const Checkbox = (props) => {

    const  handleToggleChange = () => {


    }
    if(props.mandatory === true){
        return(

            <label className="elex-switch-btn">
            <input type="checkbox"  onChange={handleToggleChange} checked />

                <div className="elex-switch-icon round"></div>
            </label>
        
        )
    }
    return(
        <label className="elex-switch-btn">
        <input type="checkbox"  onChange={handleToggleChange}  />

                <div className="elex-switch-icon round"></div>
            </label>
    
    )


}