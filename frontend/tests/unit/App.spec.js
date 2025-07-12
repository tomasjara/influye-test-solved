import axios from "axios";
import { shallowMount } from "@vue/test-utils";
import App from "@/App.vue";

jest.mock("axios", () => ({
  get: jest.fn(),
  put: jest.fn(),
  delete: jest.fn(),
}));

beforeAll(() => {
  window.axios = axios; 
});

describe("App.vue", () => {

  beforeEach(() => {
    jest.clearAllMocks();
  });

  it("getAllItems asigna items", async () => {
    const items = [{ id: 1, title: "Test", quantity: 5 }];
    axios.get.mockResolvedValue({ data: items });

    const AppTestComponent = shallowMount(App);
    await AppTestComponent.vm.getAllItems();

    expect(axios.get).toHaveBeenCalledWith("items");
    expect(AppTestComponent.vm.items).toEqual(items);
  });

});
