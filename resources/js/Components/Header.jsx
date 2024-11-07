import React from "react";

import { Link } from "@inertiajs/react";

const Header = ({backet}) => {
    

    return (<>
    <h1 className='text-4xl text-center'>
        <div className='flex flex-col'>
            НОУТБУКИ 
            <span>учебный интернет-магазин</span>
        </div>
    </h1>
    <div className='flex justify-end relative'>
        <nav>
            <Link href='/my-backet' className='btn btn-primary relative '>
            <span className={backet.length > 0 ? 'flex justify-center items-center w-[30px] rounded-[45px] bg-red-500 text-white absolute top-[-15px] left-[70px] p-[3px]' : 'hidden' }>{backet.length}</span>
                корзина
            </Link>
        </nav>
    </div>
    </>
        
    )
}

export default Header;