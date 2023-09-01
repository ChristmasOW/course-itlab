import Header from "../Header";
import Pricing from "../Pricing";
import Footer from "../Footer";
import Payment from "../Payment";
import Product from "../Product";
import Gap from "../Gap";
import Dashboard from "../Dashboard";
import Blog from "../Blog";
import Counter from "../Counter";

function Page(props) {
    return <div className="container">
        <Header/>
        <Gap/>
        <Counter value={10} incrementValue={1}/>
        <Gap/>
        <Product/>
        <Gap/>
        <Pricing/>
        <Gap/>
        <Payment/>
        <Gap/>
        <Blog/>
        <Gap/>
        <Dashboard/>
        <Gap/>
        <Footer/>
    </div>
}

export default Page;