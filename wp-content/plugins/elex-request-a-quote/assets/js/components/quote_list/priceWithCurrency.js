import {decode} from 'html-entities';

export const PriceWithCurrency = (props) => {
    const currencyPosition  = props.currency_position;
    const price = props.price;
    const currency = props.currency;

    switch(currencyPosition){
       case 'left':
        return decode(currency)+''+price
        case 'left_space':
             return decode(currency)+' '+price
       case 'right_space':
            return price+' '+decode(currency);
        case 'right':
          return  price+''+decode(currency)

    }
   
    }