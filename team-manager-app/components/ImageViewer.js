import { StyleSheet, Image } from 'react-native';

export default function ImageViewer({ placeholderImageSource, selectedImage, sizePhoto }) {
  const styles = StyleSheet.create({
    image: {
      width: sizePhoto.width,
      height: sizePhoto.height,
      borderRadius: 18,
      borderColor: 'white',
      borderWidth: 1,
    },
  });

  const imageSource = selectedImage  ? { uri: selectedImage } : placeholderImageSource;

  return <Image source={imageSource} style={styles.image} />;
}
