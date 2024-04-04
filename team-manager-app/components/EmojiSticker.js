import { View, Image } from 'react-native';
import { Gesture, GestureDetector } from 'react-native-gesture-handler';
import Animated, { useAnimatedStyle, useSharedValue, withSpring } from 'react-native-reanimated';


export default function EmojiSticker({ imageSize, stickerSource, sizePhoto }) {
  const scaleImage = useSharedValue(imageSize);
  const indexTop = -1 * sizePhoto.height

  const doubleTap = Gesture.Tap()
  .numberOfTaps(2)
  .onStart(() => {
    if (scaleImage.value !== imageSize * 2) {
      scaleImage.value = scaleImage.value * 2;
    } else {
      scaleImage.value = scaleImage.value / 2;
    }
  });

  const imageStyle = useAnimatedStyle(() => {
    return {
      width: withSpring(scaleImage.value),
      height: withSpring(scaleImage.value),
    };
  });

  const translateX = useSharedValue(0);
  const translateY = useSharedValue(0);

  const drag = Gesture.Pan()
    .onChange((event) => {
      // Limit drag and drop positions
      translateX.value += event.changeX;
      translateY.value += event.changeY;

      if (translateX.value < 0) {
        translateX.value = 0;
      }

      if (translateY.value < 0) {
        translateY.value = 0;
      }

      if (translateX.value > sizePhoto.width - scaleImage.value) {
        translateX.value = sizePhoto.width - scaleImage.value
      }

      if (translateY.value > sizePhoto.height - scaleImage.value) {
        translateY.value = sizePhoto.height - scaleImage.value
      }
    });

  const containerStyle = useAnimatedStyle(() => {
    return {
      transform: [
        {
          translateX: translateX.value,
        },
        {
          translateY: translateY.value,
        },
      ],
    };
  });

  return (
    <GestureDetector gesture={drag}>
      <Animated.View style={[containerStyle, { top: indexTop, left: 0 }]}>
        <GestureDetector gesture={doubleTap}>
          <Animated.Image
            source={stickerSource}
            resizeMode="contain"
            style={[imageStyle, { width: imageSize, height: imageSize }]}
          />
        </GestureDetector>
      </Animated.View>
    </GestureDetector>
  );
}
