import react from "react";

const Footer = () => {
    const start = 2024;
    const currentYear = new Date().getFullYear();
    
    const year = start === currentYear ? currentYear : `${start} - ${currentYear}`;
    

    return (
        <div className='flex justify-center mt-4 bg-black text-gray-500 p-4'>
            &copy; Golubev Aleksandr {year}
        </div>
    )
}

export default Footer;