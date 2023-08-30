function Image(props) {
    const imageSize = {
        width: '500',
        height: '300'
    }
    return <svg className="bd-placeholder-img bd-placeholder-img-lg featurette-image img-fluid mx-auto" width={imageSize.width} height={imageSize.height} xmlns="http://www.w3.org/2000/svg" role="img" aria-label="Placeholder: 500x500" preserveAspectRatio="xMidYMid slice" focusable="false"><title>Placeholder</title><rect width="100%" height="100%" fill="var(--bs-secondary-bg)" /><text x="50%" y="50%" fill="var(--bs-secondary-color)" dy=".3em">{imageSize.width}x{imageSize.height}</text></svg>
}

export default Image;